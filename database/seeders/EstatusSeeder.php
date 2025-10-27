<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('estatus')->insert([
            'nombre_estatus' => 'Activo',
        ]);
        DB::table('estatus')->insert([
            'nombre_estatus' => 'Inactivo',
        ]);
    }
}
