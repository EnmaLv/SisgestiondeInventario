<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {

        if (DB::getSchemaBuilder()->hasTable('rol')) {
            $hasSlug = DB::getSchemaBuilder()->hasColumn('rol', 'slug');

            $adminData = [
                'descripcion' => 'Rol por defecto Administrador',
                'menu_permissions' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if ($hasSlug) {
                $adminData['slug'] = 'administrador';
            }

            DB::table('rol')->updateOrInsert(
                ['nombre' => 'Administrador'],
                $adminData
            );

            $obreroData = [
                'descripcion' => 'Rol por defecto Obrero',
                'menu_permissions' => json_encode(['registro_comida', 'registro_diario', 'persona']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if ($hasSlug) {
                $obreroData['slug'] = 'obrero';
            }

            DB::table('rol')->updateOrInsert(
                ['nombre' => 'Obrero'],
                $obreroData
            );
        }
    }

    public function down()
    {
        if (DB::getSchemaBuilder()->hasTable('rol')) {
            DB::table('rol')->where('nombre', 'Administrador')->delete();
            DB::table('rol')->where('nombre', 'Obrero')->delete();
        }
    }
};
