<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            ['nombre_sede' => 'Acarigua', 'id_sucursal' => 1, 'estatus' => 1],
            ['nombre_sede' => 'Guanare', 'id_sucursal' => 2, 'estatus' => 1],
            ['nombre_sede' => 'Santa Rosalia', 'id_sucursal' => 3, 'estatus' => 1],
            ['nombre_sede' => 'Turen', 'id_sucursal' => 4, 'estatus' => 1]
        ]);
    }
}
