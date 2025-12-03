<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecetaIngredienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('receta_ingredientes')->insert([
            ['recetas_id' => 1, 'producto_id' => 6, 'cantidad_porcion' => 1.00, 'unidad_id' => 2],
            ['recetas_id' => 1, 'producto_id' => 1, 'cantidad_porcion' => 1.00, 'unidad_id' => 2],
            ['recetas_id' => 2, 'producto_id' => 13, 'cantidad_porcion' => 1.00, 'unidad_id' => 2],
            ['recetas_id' => 2, 'producto_id' => 1, 'cantidad_porcion' => 1.00, 'unidad_id' => 2],
            ['recetas_id' => 3, 'producto_id' => 2, 'cantidad_porcion' => 1.00, 'unidad_id' => 2],
            ['recetas_id' => 3, 'producto_id' => 1, 'cantidad_porcion' => 1.00, 'unidad_id' => 2],
            ['recetas_id' => 4, 'producto_id' => 5, 'cantidad_porcion' => 1.00, 'unidad_id' => 1],
            ['recetas_id' => 4, 'producto_id' => 1, 'cantidad_porcion' => 1.00, 'unidad_id' => 2],
            ['recetas_id' => 4, 'producto_id' => 15, 'cantidad_porcion' => 1.00, 'unidad_id' => 2],
            ['recetas_id' => 5, 'producto_id' => 14, 'cantidad_porcion' => 1.00, 'unidad_id' => 2],
            ['recetas_id' => 5, 'producto_id' => 1, 'cantidad_porcion' => 1.00, 'unidad_id' => 2],
        ]);
    }
}
