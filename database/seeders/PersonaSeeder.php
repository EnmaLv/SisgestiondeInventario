<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('persona')->insert([
            'nombre_persona' => 'Pepito ',
            'segundo_nombre_persona' => 'Canela',
            'apellido_persona' => 'Perez',
            'segundo_apellido_persona' => 'Perez',
            'cedula_persona' => '12345678',
            'telefono_persona' => '12345678',
            'genero_persona' => 'Masculino',
            'edad_persona' => 18,
            'fecha_nacimiento_persona' => '2000-01-01',
            'email_persona' => 'persona@persona.com',
            'id_perfil' => 1,
            'id_sede' => 1,
        ]);

        DB::table('persona_pnf')->insert([
            'id_persona' => 1,
            'id_pnf' => 1,
            'fecha_inicio' => '2000-01-01',
            'fecha_fin' => '2000-01-01',
        ]);
    }
}
