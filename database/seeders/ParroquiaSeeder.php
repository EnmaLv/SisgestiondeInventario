<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ParroquiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Leer el archivo JSON generado por tu script anterior
        // Asegúrate de poner el archivo en: database/data/parroquias.json
        $path = database_path('data/parroquias.json');
        
        if (!File::exists($path)) {
            $this->command->error("El archivo parroquias.json no existe en database/data/");
            return;
        }
        $json = File::get($path);
        $parroquias = json_decode($json);
        $total = count($parroquias);
        $this->command->info("Iniciando carga de $total parroquias...");
        
        // Crear la barra de progreso
        $progressBar = $this->command->getOutput()->createProgressBar($total);
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%');
        $progressBar->start();
        DB::beginTransaction();
        try {
            foreach ($parroquias as $item) {
                $municipio = DB::table('municipio')
                    ->join('estado_ve', 'municipio.id_estado_ve', '=', 'estado_ve.id_estado_ve')
                    ->where('municipio.nombre_municipio', $item->municipio)
                    ->where('estado_ve.nombre_estado_ve', $item->estado)
                    ->select('municipio.id_municipio')
                    ->first();
                if ($municipio) {
                    DB::table('parroquia')->updateOrInsert(
                        [
                            'nombre_parroquia' => $item->parroquia,
                            'id_municipio' => $municipio->id_municipio
                        ],
                        [
                            'nombre_parroquia' => $item->parroquia,
                            'id_municipio' => $municipio->id_municipio,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
                
                // Avanzar la barra de progreso
                $progressBar->advance();
            }
            DB::commit();
            $progressBar->finish();
            $this->command->newLine(2); // Espacio después de la barra
            $this->command->info("¡Carga completada! Se procesaron $total parroquias.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            $progressBar->finish();
            $this->command->error("Error al procesar las parroquias: " . $e->getMessage());
        }
    }
}
