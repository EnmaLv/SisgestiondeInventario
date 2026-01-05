<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            ['id' => 1, 'nombre_estado' => 'Amazonas'],
            ['id' => 2, 'nombre_estado' => 'Anzoátegui'],
            ['id' => 3, 'nombre_estado' => 'Apure'],
            ['id' => 4, 'nombre_estado' => 'Aragua'],
            ['id' => 5, 'nombre_estado' => 'Barinas'],
            ['id' => 6, 'nombre_estado' => 'Bolívar'],
            ['id' => 7, 'nombre_estado' => 'Carabobo'],
            ['id' => 8, 'nombre_estado' => 'Cojedes'],
            ['id' => 9, 'nombre_estado' => 'Delta Amacuro'],
            ['id' => 10, 'nombre_estado' => 'Distrito Capital'],
            ['id' => 11, 'nombre_estado' => 'Falcón'],
            ['id' => 12, 'nombre_estado' => 'Guárico'],
            ['id' => 13, 'nombre_estado' => 'Lara'],
            ['id' => 14, 'nombre_estado' => 'Mérida'],
            ['id' => 15, 'nombre_estado' => 'Miranda'],
            ['id' => 16, 'nombre_estado' => 'Monagas'],
            ['id' => 17, 'nombre_estado' => 'Nueva Esparta'],
            ['id' => 18, 'nombre_estado' => 'Portuguesa'],
            ['id' => 19, 'nombre_estado' => 'Sucre'],
            ['id' => 20, 'nombre_estado' => 'Táchira'],
            ['id' => 21, 'nombre_estado' => 'Trujillo'],
            ['id' => 22, 'nombre_estado' => 'Vargas'],
            ['id' => 23, 'nombre_estado' => 'Yaracuy'],
            ['id' => 24, 'nombre_estado' => 'Zulia'],
        ];
        $this->command->info('Estados insertados Exitosamente.');

        DB::table('estados')->insert($estados);
    }
}
