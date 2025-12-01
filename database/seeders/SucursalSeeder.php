<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SucursalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sucursals')->insert([
            ['nombre' => 'Acarigua', 'direccion' => 'Avenida Circunvalacion Sur, Sector Bellas Artes', 'telefono' => '0424-5556666', 'activo' => 1],
            ['nombre' => 'Guanare', 'direccion' => 'Ciudad de Guanare, Zona Central', 'telefono' => '0424-5556667', 'activo' => 1],
            ['nombre' => 'Santa Rosalia', 'direccion' => 'Avenida Bolivar Entre Calle 11 y 12 Frente a la a la Plaza Bolivar de el Playon', 'telefono' => '0424-5556668', 'activo' => 1],
            ['nombre' => 'Turen', 'direccion' => 'Colonia Agricola, Turen', 'telefono' => '0424-5556660', 'activo' => 1]
        ]);
    }
}
