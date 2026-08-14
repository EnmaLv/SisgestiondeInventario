<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GrupoHorarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\salud\GrupoHorario::crear([
            'user_id' => 1,
            'nombre' => 'Horario Estándar',
            'activo' => \App\Models\salud\GrupoHorario::STATUS_ACTIVE,
        ]);
    }
}
