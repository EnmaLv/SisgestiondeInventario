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
        // Sin módulo definido → siempre visible (widgets, buscador, "Módulos del Sistema")
        if (! isset($item['module']) || empty($item['module'])) {
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

        // ── Inicializar sesión si está vacía ──────────────────────────────────
        if (is_null(session('modulos_permitidos'))) {
            $this->inicializarSesion($userId);
        }

        $permitidos   = session('modulos_permitidos', []);
        $esAdmin      = session('es_admin', false);
        $moduloActivo = session('modulo_activo', null);

        // Sin módulos asignados → ocultar todo
        if (empty($permitidos)) {
            return false;
        }

        // El ítem no pertenece a ningún módulo permitido → ocultar
        if (! in_array($item['module'], $permitidos)) {
            return false;
        }

        // ── Administrador ─────────────────────────────────────────────────────
        // No ve nada hasta seleccionar un módulo. Una vez seleccionado, solo ese.
        if ($esAdmin) {
            if (is_null($moduloActivo)) {
                return false;
            }
            return $item['module'] === $moduloActivo ? $item : false;
        }

        // ── Otros roles ───────────────────────────────────────────────────────
        // Si ya hay módulo activo, filtrar por él
        if (! is_null($moduloActivo)) {
            return $item['module'] === $moduloActivo ? $item : false;
        }

        // Sin módulo activo: activar el primero automáticamente
        session(['modulo_activo' => $permitidos[0]]);
        return $item['module'] === $permitidos[0] ? $item : false;
    }

    private function inicializarSesion(int $userId): void
    {
        $modulesTable = Schema::hasTable('modulos') ? 'modulos' : (Schema::hasTable('modulo') ? 'modulo' : null);

        if (! $modulesTable) {
            session(['modulos_permitidos' => [], 'es_admin' => false]);
            return;
        }

        $roleIds = DB::table('rol_usuario')
            ->where('id_usuario', $userId)
            ->pluck('id_rol')
            ->toArray();

        if (empty($roleIds)) {
            session(['modulos_permitidos' => [], 'es_admin' => false]);
            return;
        }

        $esAdmin = DB::table('rol')
            ->whereIn('id_rol', $roleIds)
            ->where(function ($q) {
                $q->where('slug', 'administrador')
                    ->orWhere('nombre', 'Administrador')
                    ->orWhere('slug', 'secretaria-de-bienestar')
                    ->orWhere('nombre', 'Secretaria De Bienestar');
            })
            ->exists();

        if ($esAdmin) {
            // Admin: acceso a todos los módulos activos
            $permitidos = DB::table($modulesTable)
                ->where('activo', 1)
                ->pluck('key')
                ->toArray();
        } else {
            // Otros roles: solo módulos asignados
            $permitidos = DB::table('rol_modulo')
                ->join($modulesTable, $modulesTable . '.id', '=', 'rol_modulo.modulo_id')
                ->whereIn('rol_modulo.rol_id', $roleIds)
                ->where($modulesTable . '.activo', 1)
                ->distinct()
                ->pluck($modulesTable . '.key')
                ->toArray();
        }

        session([
            'modulos_permitidos' => $permitidos,
            'es_admin'           => $esAdmin,
        ]);
    }
}
