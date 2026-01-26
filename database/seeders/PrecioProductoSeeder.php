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
            ['producto_id' => 2, 'costo_usd' => 1.20, 'margen' => 15, 'precio_usd' => null],
            ['producto_id' => 3, 'costo_usd' => 1.25, 'margen' => 25, 'precio_usd' => null],
            ['producto_id' => 4, 'costo_usd' => 5.99, 'margen' => 18, 'precio_usd' => null],
            ['producto_id' => 5, 'costo_usd' => 5.20, 'margen' => 10, 'precio_usd' => null],
            ['producto_id' => 6, 'costo_usd' => 2.60, 'margen' => 12, 'precio_usd' => null],
            ['producto_id' => 7, 'costo_usd' => 8.73, 'margen' => 20, 'precio_usd' => null],
            ['producto_id' => 8, 'costo_usd' => 1.82, 'margen' => 15, 'precio_usd' => null],
            ['producto_id' => 9, 'costo_usd' => 1.50, 'margen' => 22, 'precio_usd' => null],
            ['producto_id' => 10, 'costo_usd' => 1.00, 'margen' => 18, 'precio_usd' => null],
            ['producto_id' => 11, 'costo_usd' => 1.80, 'margen' => 18, 'precio_usd' => null],
            ['producto_id' => 12, 'costo_usd' => 2.01, 'margen' => 18, 'precio_usd' => null],
            ['producto_id' => 13, 'costo_usd' => 2.50, 'margen' => 18, 'precio_usd' => null],
            ['producto_id' => 14, 'costo_usd' => 4.00, 'margen' => 18, 'precio_usd' => null],
            ['producto_id' => 15, 'costo_usd' => 6.08, 'margen' => 18, 'precio_usd' => null],
            ['producto_id' => 16, 'costo_usd' => 6.18, 'margen' => 18, 'precio_usd' => null],
            ['producto_id' => 17, 'costo_usd' => 7.60, 'margen' => 18, 'precio_usd' => null],
            ['producto_id' => 18, 'costo_usd' => 3.00, 'margen' => 20, 'precio_usd' => null],
            ['producto_id' => 19, 'costo_usd' => 5.06, 'margen' => 18, 'precio_usd' => null],
            ['producto_id' => 20, 'costo_usd' => 1.43, 'margen' => 25, 'precio_usd' => null],
            ['producto_id' => 21, 'costo_usd' => 1.32, 'margen' => 30, 'precio_usd' => null],
            ['producto_id' => 22, 'costo_usd' => 1.10, 'margen' => 25, 'precio_usd' => null],
            ['producto_id' => 23, 'costo_usd' => 1.47, 'margen' => 25, 'precio_usd' => null],
            ['producto_id' => 24, 'costo_usd' => 1.20, 'margen' => 20, 'precio_usd' => null],
            ['producto_id' => 25, 'costo_usd' => 0.75, 'margen' => 22, 'precio_usd' => null],
            ['producto_id' => 26, 'costo_usd' => 1.70, 'margen' => 18, 'precio_usd' => null],
            ['producto_id' => 27, 'costo_usd' => 2.00, 'margen' => 20, 'precio_usd' => null],
            ['producto_id' => 28, 'costo_usd' => 1.00, 'margen' => 30, 'precio_usd' => null],
            ['producto_id' => 29, 'costo_usd' => 2.82, 'margen' => 35, 'precio_usd' => null],
            ['producto_id' => 30, 'costo_usd' => 0.55, 'margen' => 40, 'precio_usd' => null],
            ['producto_id' => 31, 'costo_usd' => 0.50, 'margen' => 40, 'precio_usd' => null],
        ]);

    }
}
