<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoVeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            ['nombre_estado_ve' => 'Amazonas'],
            ['nombre_estado_ve' => 'Anzoátegui'],
            ['nombre_estado_ve' => 'Apure'],
            ['nombre_estado_ve' => 'Aragua'],
            ['nombre_estado_ve' => 'Barinas'],
            ['nombre_estado_ve' => 'Bolívar'],
            ['nombre_estado_ve' => 'Carabobo'],
            ['nombre_estado_ve' => 'Cojedes'],
            ['nombre_estado_ve' => 'Delta Amacuro'],
            ['nombre_estado_ve' => 'Distrito Capital'],
            ['nombre_estado_ve' => 'Falcón'],
            ['nombre_estado_ve' => 'Guárico'],
            ['nombre_estado_ve' => 'Lara'],
            ['nombre_estado_ve' => 'Mérida'],
            ['nombre_estado_ve' => 'Miranda'],
            ['nombre_estado_ve' => 'Monagas'],
            ['nombre_estado_ve' => 'Nueva Esparta'],
            ['nombre_estado_ve' => 'Portuguesa'],
            ['nombre_estado_ve' => 'Sucre'],
            ['nombre_estado_ve' => 'Táchira'],
            ['nombre_estado_ve' => 'Trujillo'],
            ['nombre_estado_ve' => 'Vargas'],
            ['nombre_estado_ve' => 'Yaracuy'],
            ['nombre_estado_ve' => 'Zulia']
        ];
        // Insertar solo los que no existen
        foreach ($estados as $estado) {
            DB::table('estado_ve')->updateOrInsert(
                ['nombre_estado_ve' => $estado['nombre_estado_ve']],
                $estado
            );
        }
    }
}
