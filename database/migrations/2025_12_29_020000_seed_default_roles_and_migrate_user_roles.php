<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('rol')) {
            return;
        }

        $defaults = [
            ['nombre' => 'Administrador', 'descripcion' => 'Rol con todos los permisos'],
            ['nombre' => 'Obrero', 'descripcion' => 'Rol operativo'],
        ];

        foreach ($defaults as $d) {
            $exists = DB::table('rol')->where('nombre', $d['nombre'])->exists();
            if (! $exists) {
                $slug = strtolower(str_replace(' ', '-', $d['nombre']));
                DB::table('rol')->insert(array_merge($d, ['slug' => $slug, 'created_at' => now(), 'updated_at' => now()]));
            }
        }

        // Migrate existing usuario.role values into pivot table rol_usuario
        if (! Schema::hasTable('usuario') || ! Schema::hasTable('rol_usuario')) {
            return;
        }

        // Only migrate legacy 'role' values if the column exists
        if (Schema::hasColumn('usuario', 'role')) {
            $usuarios = DB::table('usuario')->select('id_usuario','role')->whereNotNull('role')->where('role','<>','')->get();

            foreach ($usuarios as $u) {
            $rol = DB::table('rol')->where('nombre', $u->role)->first();
            if (! $rol) {
                // Create a matching role to preserve assignment
                $slug = strtolower(str_replace(' ', '-', $u->role));
                $id = DB::table('rol')->insertGetId(['nombre' => $u->role, 'slug' => $slug, 'descripcion' => 'Creado desde migración de roles', 'created_at' => now(), 'updated_at' => now()]);
            } else {
                $id = $rol->id_rol;
            }

            // insert pivot if not exists
            $existsPivot = DB::table('rol_usuario')->where('id_rol', $id)->where('id_usuario', $u->id_usuario)->exists();
            if (! $existsPivot) {
                DB::table('rol_usuario')->insert(['id_rol' => $id, 'id_usuario' => $u->id_usuario, 'created_at' => now(), 'updated_at' => now()]);
            }
            }
        }
    }

    public function down(): void
    {
        // We won't remove roles or pivot entries on rollback to avoid data loss
    }
};
