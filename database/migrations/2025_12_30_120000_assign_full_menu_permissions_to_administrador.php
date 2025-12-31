<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration will ensure that a role named "Administrador" exists and
     * that its `menu_permissions` column contains all keys found in the
     * `adminlte.menu` configuration. It is idempotent.
     *
     * Note: this migration reads config at runtime; ensure config('adminlte.menu')
     * is available when running migrations (it usually is).
     *
     * @return void
     */
    public function up()
    {
        // Collect menu keys from config/adminlte.php
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
            // nothing to do
            return;
        }

        // Ensure role exists
        $rol = DB::table('rol')->where('nombre', 'Administrador')->first();
        if (!$rol) {
            // Insert a basic Administrador role
            $id = DB::table('rol')->insertGetId([
                'nombre' => 'Administrador',
                'descripcion' => 'Rol administrador creado por migración',
                'menu_permissions' => json_encode($allKeys),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            // Update existing role's menu_permissions
            DB::table('rol')->where('id_rol', $rol->id_rol)->update([
                'menu_permissions' => json_encode($allKeys),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * This will not remove the Administrador role, but will clear its
     * `menu_permissions` column to an empty array (safe rollback).
     *
     * @return void
     */
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
