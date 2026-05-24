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
        $user = Auth::user();
        if (! $user) {
            return false;
        }

        $userId = $user->id_usuario ?? $user->id ?? null;
        if (! $userId) {
            return false;
        }

        if (is_null(session('modulos_permitidos')) || is_null(session('menu_permissions_user'))) {
            $this->inicializarSesion($userId);
        }

        $permitidos      = session('modulos_permitidos', []);
        $menuPermissions = session('menu_permissions_user', []);
        $esAdmin         = session('es_admin', false);
        $moduloActivo    = session('modulo_activo', null);

        // 1. Filtrado por llave de menú (Permisos tradicionales)
        if (isset($item['key']) && ! empty($item['key'])) {
            if (! $esAdmin && ! in_array($item['key'], $menuPermissions)) {
                return false;
            }
        }

        // 2. Filtrado por Módulo Dinámico
        if (isset($item['module']) && ! empty($item['module'])) {

            // Si el ítem requiere módulo, pero no hay ninguno activo, se oculta (Aplica para Admin/Secretaria)
            if (is_null($moduloActivo)) {
                return false;
            }

            // Si hay un módulo activo, pero este ítem pertenece a otro, se oculta
            if ($item['module'] !== $moduloActivo) {
                return false;
            }

            // Verificación de seguridad
            if (! in_array($item['module'], $permitidos)) {
                return false;
            }
        }

        return $item;
    }

    private function inicializarSesion($userId)
    {
        $modulesTable = Schema::hasTable('modulos') ? 'modulos' : (Schema::hasTable('modulo') ? 'modulo' : null);

        if (! $modulesTable) {
            session([
                'modulos_permitidos'    => [],
                'menu_permissions_user' => [],
                'es_admin'              => false
            ]);
            return;
        }

        $roles = DB::table('rol_usuario')
            ->join('rol', 'rol.id_rol', '=', 'rol_usuario.id_rol')
            ->where('rol_usuario.id_usuario', $userId)
            ->get();

        if ($roles->isEmpty()) {
            session([
                'modulos_permitidos'    => [],
                'menu_permissions_user' => [],
                'es_admin'              => false
            ]);
            return;
        }

        $roleIds = $roles->pluck('id_rol')->toArray();

        $esAdmin = $roles->contains(function ($rol) {
            $nombreLower = strtolower($rol->nombre);
            $slugLower   = strtolower($rol->slug ?? '');
            return in_array($nombreLower, ['administrador', 'secretaria de bienestar']) ||
                in_array($slugLower, ['administrador', 'secretaria-de-bienestar']);
        });

        $menuPermissions = [];
        foreach ($roles as $rol) {
            $perms = json_decode($rol->menu_permissions, true) ?? [];
            if (is_array($perms)) {
                $menuPermissions = array_merge($menuPermissions, $perms);
            }
        }
        $menuPermissions = array_values(array_unique($menuPermissions));

        if ($esAdmin) {
            $permitidos = DB::table($modulesTable)
                ->where('activo', 1)
                ->pluck('key')
                ->toArray();
        } else {
            $permitidos = DB::table('rol_modulo')
                ->join($modulesTable, $modulesTable . '.id', '=', 'rol_modulo.modulo_id')
                ->whereIn('rol_modulo.rol_id', $roleIds)
                ->where($modulesTable . '.activo', 1)
                ->distinct()
                ->pluck($modulesTable . '.key')
                ->toArray();
        }

        session([
            'modulos_permitidos'    => $permitidos,
            'menu_permissions_user' => $menuPermissions,
            'es_admin'              => $esAdmin,
        ]);

        if (! $esAdmin && count($permitidos) === 1) {
            session(['modulo_activo' => $permitidos[0]]);
        }
    }
}
