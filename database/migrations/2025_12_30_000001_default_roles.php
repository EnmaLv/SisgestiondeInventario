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
                'descripcion' => 'Rol por defecto Secretaria De Bienestar',
                'menu_permissions' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if ($hasSlug) {
                $secretariaData['slug'] = 'secretaria-de-bienestar';
            }
            DB::table('rol')->updateOrInsert(
                ['nombre' => 'Secretaria De Bienestar'],
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
                'menu_permissions' => json_encode(['envases_primarios', 'categorias_medicamentos', 'medicamentos', 'enfermedades_salud']),
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
                'menu_permissions' => json_encode(['envases_primarios', 'categorias_medicamentos', 'medicamentos', 'enfermedades_salud']),
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
            $administradorBecas = [
                'descripcion' => 'Rol por defecto Administrador de Beca',
                'menu_permissions' => json_encode(['becas', 'solicitudes_becas']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if ($hasSlug) {
                $administradorBecas['slug'] = 'administrador-de-beca';
            }
            DB::table('rol')->updateOrInsert(
                ['nombre' => 'Administrador de Beca'],
                $administradorBecas
            );

            $estudianteData = [
                'descripcion' => 'Rol por defecto paciente',
                'menu_permissions' => json_encode(['citas', 'mural', 'estado_animo_diario']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if ($hasSlug) {
                $estudianteData['slug'] = 'paciente';
            }
            DB::table('rol')->updateOrInsert(
                ['nombre' => 'Paciente'],
                $estudianteData
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
            DB::table('rol')->where('nombre', 'Administrador de Beca')->delete();
            DB::table('rol')->where('nombre', 'Paciente')->delete();
        }
    }
};
