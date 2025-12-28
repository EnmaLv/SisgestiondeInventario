<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrecioProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('precio_productos')->insert([
            ['producto_id' => 1, 'costo_usd' => 1.44, 'margen' => 20, 'precio_usd' => null],
            ['producto_id' => 2, 'costo_usd' => 4.47, 'margen' => 15, 'precio_usd' => null],
            ['producto_id' => 3, 'costo_usd' => 2.19, 'margen' => 25, 'precio_usd' => null],
            ['producto_id' => 4, 'costo_usd' => 5.99, 'margen' => 18, 'precio_usd' => null],
            ['producto_id' => 5, 'costo_usd' => 5.89, 'margen' => 10, 'precio_usd' => null],
            ['producto_id' => 6, 'costo_usd' => 2.78, 'margen' => 12, 'precio_usd' => null],
            ['producto_id' => 7, 'costo_usd' => 9.50, 'margen' => 20, 'precio_usd' => null],
            ['producto_id' => 8, 'costo_usd' => 1.40, 'margen' => 15, 'precio_usd' => null],
            ['producto_id' => 9, 'costo_usd' => 0.95, 'margen' => 22, 'precio_usd' => null],
            ['producto_id' => 10, 'costo_usd' => 0.50, 'margen' => 18, 'precio_usd' => null],
            ['producto_id' => 11, 'costo_usd' => 0.42, 'margen' => 18, 'precio_usd' => null],
            ['producto_id' => 12, 'costo_usd' => 2.23, 'margen' => 18, 'precio_usd' => null],
            ['producto_id' => 13, 'costo_usd' => 0.30, 'margen' => 18, 'precio_usd' => null],
            ['producto_id' => 14, 'costo_usd' => 6.67, 'margen' => 18, 'precio_usd' => null],
            ['producto_id' => 15, 'costo_usd' => 6.83, 'margen' => 18, 'precio_usd' => null],
            ['producto_id' => 16, 'costo_usd' => 5.88, 'margen' => 18, 'precio_usd' => null],
            ['producto_id' => 17, 'costo_usd' => 4.38, 'margen' => 18, 'precio_usd' => null],

            // ... agrega más según tus productos
        ]);

    }
}
