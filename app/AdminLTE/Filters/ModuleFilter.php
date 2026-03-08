<?php

namespace App\AdminLTE\Filters;

use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModuleFilter implements FilterInterface
{
    public function transform($item)
    {
        // Ítems sin módulo asignado (buscador, widgets, "Módulos del Sistema") → siempre visibles
        if (! isset($item['module']) || empty($item['module'])) {
            return $item;
        }

        $modulesTable = Schema::hasTable('modulos') ? 'modulos' : (Schema::hasTable('modulo') ? 'modulo' : null);
        if (! $modulesTable) {
            return $item;
        }

        $user = Auth::user();
        if (! $user) {
            return false;
        }

        $userId = $user->id_usuario ?? $user->id ?? null;
        if (! $userId) {
            return false;
        }

        // ── Construir lista de módulos permitidos (con caché en sesión) ──
        $permitidos = session('modulos_permitidos', null);

        if (is_null($permitidos)) {
            $roleIds = DB::table('rol_usuario')
                ->where('id_usuario', $userId)
                ->pluck('id_rol')
                ->toArray();

            if (empty($roleIds)) {
                session(['modulos_permitidos' => []]);
                return false;
            }

            $esAdmin = DB::table('rol')
                ->whereIn('id_rol', $roleIds)
                ->where(function ($q) {
                    $q->where('slug', 'administrador')
                      ->orWhere('nombre', 'Administrador');
                })
                ->exists();

            if ($esAdmin) {
                // Admin puede ver todos los módulos activos
                $permitidos = DB::table($modulesTable)
                    ->where('activo', 1)
                    ->pluck('key')
                    ->toArray();
            } else {
                // Otros roles: solo los módulos asignados a sus roles
                $permitidos = DB::table('rol_modulo')
                    ->join($modulesTable, $modulesTable . '.id', '=', 'rol_modulo.modulo_id')
                    ->whereIn('rol_modulo.rol_id', $roleIds)
                    ->where($modulesTable . '.activo', 1)
                    ->distinct()
                    ->pluck($modulesTable . '.key')
                    ->toArray();
            }

            session(['modulos_permitidos' => $permitidos]);
            session(['es_admin' => $esAdmin]);
        }

        if (empty($permitidos)) {
            return false;
        }

        // El ítem no está entre los módulos permitidos del usuario → ocultar
        if (! in_array($item['module'], $permitidos)) {
            return false;
        }

        $esAdmin     = session('es_admin', false);
        $moduloActivo = session('modulo_activo', null);

        // ── Administrador ──
        // Sin módulo activo seleccionado: ocultar todo (solo verá "Módulos del Sistema")
        // Con módulo activo seleccionado: mostrar solo ese módulo
        if ($esAdmin) {
            if (is_null($moduloActivo)) {
                return false;
            }
            return $item['module'] === $moduloActivo ? $item : false;
        }

        // ── Otros roles ──
        // Si tiene un solo módulo o ya tiene uno activo, respetar ese filtro
        if (! is_null($moduloActivo)) {
            return $item['module'] === $moduloActivo ? $item : false;
        }

        // Sin módulo activo y tiene un solo módulo: activarlo automáticamente
        if (count($permitidos) === 1) {
            session(['modulo_activo' => $permitidos[0]]);
            return $item['module'] === $permitidos[0] ? $item : false;
        }

        // Varios módulos sin selección: mostrar el primero por defecto
        session(['modulo_activo' => $permitidos[0]]);
        return $item['module'] === $permitidos[0] ? $item : false;
    }
}