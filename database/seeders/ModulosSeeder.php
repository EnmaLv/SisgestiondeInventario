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
        ];

        foreach ($modulos as $m) {
            DB::table('modulos')->updateOrInsert(['key' => $m['key']], $m);
        }
    }
}