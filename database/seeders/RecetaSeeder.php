<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('recetas')->insert([
            ['nombre' => 'Arepa Con Carne Molida', 'descripcion' => null,  'estado' => 1],
            ['nombre' => 'Arepa Con Salchicha', 'descripcion' => null,  'estado' => 1],
            ['nombre' => 'Arepa Con Mortadela', 'descripcion' => null,  'estado' => 1],
            ['nombre' => 'Arepa Con Mantequilla y Queso', 'descripcion' => null,  'estado' => 1],
            ['nombre' => 'Arepa Con Pollo', 'descripcion' => null,  'estado' => 1],
            ['nombre' => 'Arepa Con Carne Mechada', 'descripcion' => null,  'estado' => 1],
            ['nombre' => 'Arepa Con Jamon y Queso', 'descripcion' => null,  'estado' => 1],
        ]);
    }
}
