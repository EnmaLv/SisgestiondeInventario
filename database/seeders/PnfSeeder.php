<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PnfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pnfs = [
            ['nombre_pnf' => 'INFORMATICA', 'id_estatus' => 1],
            ['nombre_pnf' => 'ELECTRICIDAD', 'id_estatus' => 1],
            ['nombre_pnf' => 'AGROALIMENTACION', 'id_estatus' => 1],
            ['nombre_pnf' => 'ADMINISTRACION', 'id_estatus' => 1],
            ['nombre_pnf' => 'VETERINARIA', 'id_estatus' => 1],
            ['nombre_pnf' => 'MECANICA', 'id_estatus' => 1],
            ['nombre_pnf' => 'MANTENIMIENTO', 'id_estatus' => 1],
            ['nombre_pnf' => 'DISTRIBUCIÓN LOGÍSTICA', 'id_estatus' => 1],
            ['nombre_pnf' => 'PROC. Y DIST. DE ALIMENTOS', 'id_estatus' => 1],
            ['nombre_pnf' => 'SEGURIDAD ALIMENTARIA', 'id_estatus' => 1],
        ];
        $this->command->info('Pnfs insertados Exitosamente.');
        DB::table('pnf')->insert($pnfs);
    }
}
