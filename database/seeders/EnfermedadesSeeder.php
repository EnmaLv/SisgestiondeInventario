<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class EnfermedadesSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('seeders/data/cie10.json');

        if (!File::exists($jsonPath)) {
            $this->command->error("No se encontró el archivo cie10.json en: {$jsonPath}");
            return;
        }

        $json = File::get($jsonPath);
        $enfermedades = json_decode($json, true);

        if (empty($enfermedades)) {
            $this->command->error("El archivo JSON está vacío o mal formateado.");
            return;
        }

        // Desactivar temporalmente revisión de claves foráneas para acelerar la inserción
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('enfermedades')->truncate();

        // Dividir los 12k registros en lotes de 1.000
        $chunks = array_chunk($enfermedades, 1000);

        foreach ($chunks as $chunk) {
            $insertData = [];

            foreach ($chunk as $item) {
                $codigo = trim($item['code'] ?? '');
                
                // Determinar si pertenece a salud mental (Capítulo V de CIE-10 empieza por F)
                $esMental = str_starts_with($codigo, 'F') || str_contains($codigo, 'F00') || str_contains($codigo, 'F99');

                $insertData[] = [
                    'codigo'     => $codigo ?: null,
                    'nombre'     => trim($item['description'] ?? ''),
                    'categoria'  => $esMental ? 'mental' : 'fisica',
                    'nivel'      => $item['level'] ?? 0,
                    'activo'     => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('enfermedades')->insert($insertData);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->info('¡Tabla enfermedades poblada exitosamente con CIE-10!');
    }
}