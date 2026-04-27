<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Sucursal;
use App\Models\Sede;

class SedeSeeder extends Seeder
{

    public function run(): void
    {
        Sucursal::unsetEventDispatcher();

        $sucursales = [
            ['nombre' => 'Acarigua'],
            ['nombre' => 'Guanare'],
            ['nombre' => 'Santa Rosalia'],
            ['nombre' => 'Turen'],
        ];

        foreach ($sucursales as $s) {
            $sucursal = Sucursal::updateOrCreate(['nombre' => $s['nombre']], [
                'direccion' => 'Dirección por defecto',
                'telefono' => '0000',
            ]);

            DB::table('sede')->updateOrInsert(
                ['id_sucursal' => $sucursal->id],
                ['nombre_sede' => $sucursal->nombre, 'estatus' => 1]
            );
        }
    }
}
