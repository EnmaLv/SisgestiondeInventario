<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SedeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sede')->insert([
            ['nombre' => 'Acarigua', 'direccion' => 'Bellas Artes', 'telefono' => '0424-2222232', 'activo' => 1],
            ['nombre' => 'Guanare', 'direccion' => 'Ciudad de Guanare, Zona Central', 'telefono' => '0424-5556667', 'activo' => 1],
            ['nombre' => 'Santa Rosalia', 'direccion' => 'Avenida Bolivar Entre Calle 11 y 12 Frente a la Plaza Bolivar de El Playon', 'telefono' => '0424-5556668', 'activo' => 1],
            ['nombre' => 'Turen', 'direccion' => 'Colonia Agricola, Turen', 'telefono' => '0424-5556660', 'activo' => 1]
        ]);
    }
}