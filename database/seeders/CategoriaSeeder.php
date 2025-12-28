<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categorias')->insert([
            ['nombre' => 'Embutidos', 'descripcion' => null],
            ['nombre' => 'Frutas', 'descripcion' => null],
            ['nombre' => 'Verduras', 'descripcion' => null],
            ['nombre' => 'Legumbres', 'descripcion' => null],
            ['nombre' => 'Carnes', 'descripcion' => null],
            ['nombre' => 'Lácteos', 'descripcion' => null],
            ['nombre' => 'Cereales', 'descripcion' => null],
            ['nombre' => 'Aceite', 'descripcion' => null],
            ['nombre' => 'Carbohidrato', 'descripcion' => null],
            ['nombre' => 'Grasas', 'descripcion' => null]
        ]);
    }
}
