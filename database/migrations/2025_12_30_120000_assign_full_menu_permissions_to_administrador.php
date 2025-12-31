<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        
        $menu = config('adminlte.menu', []);
        $allKeys = [];

        $collector = function ($items) use (&$collector, &$allKeys) {
            foreach ($items as $it) {
                if (isset($it['submenu']) && is_array($it['submenu'])) {
                    $collector($it['submenu']);
                } else {
                    $val = $it['key'] ?? ($it['url'] ?? ($it['route'] ?? null));
                    if ($val) $allKeys[] = $val;
                }
            }
        };

        $collector($menu);
        $allKeys = array_values(array_unique($allKeys));

        if (empty($allKeys)) {
          
            return;
        }

       
        $rol = DB::table('rol')->where('nombre', 'Administrador')->first();
        if (!$rol) {
       
            $id = DB::table('rol')->insertGetId([
                'nombre' => 'Administrador',
                'descripcion' => 'Rol administrador creado por migración',
                'menu_permissions' => json_encode($allKeys),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
           
            DB::table('rol')->where('id_rol', $rol->id_rol)->update([
                'menu_permissions' => json_encode($allKeys),
                'updated_at' => now(),
            ]);
        }
    }

public function down()
    {
        $rol = DB::table('rol')->where('nombre', 'Administrador')->first();
        if ($rol) {
            DB::table('rol')->where('id_rol', $rol->id_rol)->update([
                'menu_permissions' => json_encode([]),
                'updated_at' => now(),
            ]);
        }
    }
};
