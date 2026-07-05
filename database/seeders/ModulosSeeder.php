<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModulosSeeder extends Seeder
{
    public function run()
    {
        $modulos = [
            ['key' => 'salud', 'nombre' => 'Salud', 'descripcion' => 'Módulo de atención médica'],
            ['key' => 'comedor', 'nombre' => 'Comedor', 'descripcion' => 'Módulo de comedor'],
            ['key' => 'administracion', 'nombre' => 'Administración', 'descripcion' => 'Configuración y usuarios'],
            ['key' => 'beca', 'nombre' => 'Beca', 'descripcion' => 'Módulo de beca'],
            ['key' => 'transporte', 'nombre' => 'Transporte', 'descripcion' => 'Módulo de transporte universitario'],
        ];

        foreach ($modulos as $m) {
            DB::table('modulos')->updateOrInsert(['key' => $m['key']], $m);
        }
    }
}