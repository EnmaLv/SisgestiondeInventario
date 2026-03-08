<?php

namespace App\AdminLTE\Filters;

use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ModuleFilter implements FilterInterface
{
    public function transform($item)
    {
        if (! isset($item['module']) || empty($item['module'])) {
            return $item;
        }

        $moduloActivo = session('modulo_activo');

        if (is_null($moduloActivo)) {
            $user = Auth::user();
            $rol = DB::table('rol')->where('id_rol', $user->id_perfil)->first();

            if ($rol && $rol->modulo) {
                $moduloActivo = $rol->modulo;
            }
        }

        if ($item['module'] === $moduloActivo) {
            return $item;
        }

        return false;
    }
}
