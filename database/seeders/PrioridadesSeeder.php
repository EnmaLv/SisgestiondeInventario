<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrioridadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prioridades = [
            ['nombre' => 'baja', 'nivel_gravedad' => 1, 'psicologo_id' => null, 'activo' => 1],
            ['nombre' => 'media', 'nivel_gravedad' => 5, 'psicologo_id' => null, 'activo' => 1],
            ['nombre' => 'alta', 'nivel_gravedad' => 7, 'psicologo_id' => null, 'activo' => 1],
            ['nombre' => 'crítica', 'nivel_gravedad' => 10, 'psicologo_id' => null, 'activo' => 1],
        ];

        foreach ($prioridades as $prioridad) {
            $exists = DB::table('prioridades')
                ->where('nombre', $prioridad['nombre'])
                ->whereNull('psicologo_id')
                ->first();

            if ($exists) {
                DB::table('prioridades')
                    ->where('id', $exists->id)
                    ->update([
                        'nivel_gravedad' => $prioridad['nivel_gravedad'],
                        'activo' => 1,
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('prioridades')->insert(array_merge($prioridad, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }
}
