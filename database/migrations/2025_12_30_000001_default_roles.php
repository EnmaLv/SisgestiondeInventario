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

            $secretariaData = [
                'descripcion' => 'Rol por defecto Secretaria',
                'menu_permissions' => json_encode(['registro_comida', 'registro_diario', 'persona']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if ($hasSlug) {
                $secretariaData['slug'] = 'secretaria';
            }
            DB::table('rol')->updateOrInsert(
                ['nombre' => 'Secretaria'],
                $secretariaData
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

            $administradorSaludData = [
                'descripcion' => 'Rol por defecto Administrador de Salud',
                'menu_permissions' => json_encode(['envases_primarios', 'categorias_medicamentos']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if ($hasSlug) {
                $administradorSaludData['slug'] = 'administrador-de-salud';
            }
            DB::table('rol')->updateOrInsert(
                ['nombre' => 'Administrador de Salud'],
                $administradorSaludData
            );

            $secretariaSaludData = [
                'descripcion' => 'Rol por defecto Secretaria de Salud',
                'menu_permissions' => json_encode(['registro_comida', 'registro_diario', 'persona']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if ($hasSlug) {
                $secretariaSaludData['slug'] = 'secretaria-de-salud';
            }
            DB::table('rol')->updateOrInsert(
                ['nombre' => 'Secretaria de Salud'],
                $secretariaSaludData
            );
        }
    }

    public function down()
    {
        if (DB::getSchemaBuilder()->hasTable('rol')) {
            DB::table('rol')->where('nombre', 'Administrador')->delete();
            DB::table('rol')->where('nombre', 'Obrero')->delete();
            DB::table('rol')->where('nombre', 'Administrador de Salud')->delete();
            DB::table('rol')->where('nombre', 'Secretaria')->delete();
            DB::table('rol')->where('nombre', 'Secretaria de Salud')->delete();
        }
    }
};
