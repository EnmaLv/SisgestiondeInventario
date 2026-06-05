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
            ['nombre' => 'Embutidos', 'descripcion' => null, 'tipo_producto_id' => 1],
            ['nombre' => 'Frutas', 'descripcion' => null, 'tipo_producto_id' => 1],
            ['nombre' => 'Verduras', 'descripcion' => null, 'tipo_producto_id' => 1],
            ['nombre' => 'Legumbres', 'descripcion' => null, 'tipo_producto_id' => 1],
            ['nombre' => 'Carnes', 'descripcion' => null, 'tipo_producto_id' => 1],
            ['nombre' => 'Lácteos', 'descripcion' => null, 'tipo_producto_id' => 1],
            ['nombre' => 'Cereales', 'descripcion' => null, 'tipo_producto_id' => 1],
            ['nombre' => 'Aceite', 'descripcion' => null, 'tipo_producto_id' => 1],
            ['nombre' => 'Carbohidrato', 'descripcion' => null, 'tipo_producto_id' => 1],
            ['nombre' => 'Grasas', 'descripcion' => null, 'tipo_producto_id' => 1],
            ['nombre' => 'Bebidas', 'descripcion' => null, 'tipo_producto_id' => 1],
            ['nombre' => 'Enlatados', 'descripcion' => null, 'tipo_producto_id' => 1],
            ['nombre' => 'Condimentos', 'descripcion' => null, 'tipo_producto_id' => 1],
        ]);
    }
}
