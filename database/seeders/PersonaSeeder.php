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
            'nombre_persona' => 'ENMANUEL',
            'segundo_nombre_persona' => 'JESUS',
            'apellido_persona' => 'MEDINA',
            'segundo_apellido_persona' => 'BARROS',
            'cedula_persona' => '31008661',
            'telefono_persona' => '04245994343',
            'genero_persona' => 'MASCULINO',
            'edad_persona' => \Carbon\Carbon::parse('2005-07-11')->age,
            'fecha_nacimiento_persona' => '2005-07-11',
            'email_persona' => 'medina.enma1234@gmail.com',
            'semestre_persona' => '4to SEMESTRE',
            'id_perfil' => 2,
            'id_sede' => 1,
        ]);

        DB::table('persona_pnf')->insert([
            'id_persona' => 1,
            'id_pnf' => 1,
            'fecha_inicio' => now()->toDateString(),
            'fecha_fin' => now()->toDateString(),
        ]);

        DB::table('persona')->insert([
            'nombre_persona' => 'ANGEL ',
            'segundo_nombre_persona' => 'JESUS',
            'apellido_persona' => 'LINAREZ',
            'segundo_apellido_persona' => 'MARTINEZ',
            'cedula_persona' => '31216160',
            'telefono_persona' => '04245720593',
            'genero_persona' => 'MASCULINO',
            'edad_persona' => \Carbon\Carbon::parse('2006-02-08')->age,
            'fecha_nacimiento_persona' => '2006-02-08',
            'email_persona' => 'angeljlinarez@gmail.com',
            'semestre_persona' => '4to SEMESTRE',
            'id_perfil' => 2,
            'id_sede' => 1,
        ]);

        DB::table('persona_pnf')->insert([
            'id_persona' => 2,
            'id_pnf' => 1,
            'fecha_inicio' => now()->toDateString(),
            'fecha_fin' => now()->toDateString(),
        ]);

        DB::table('persona')->insert([
            'nombre_persona' => 'NOHELY',
            'segundo_nombre_persona' => 'ROXANA',
            'apellido_persona' => 'SOSA',
            'segundo_apellido_persona' => 'QUINTERO',
            'cedula_persona' => '31710990',
            'telefono_persona' => '04129242220',
            'genero_persona' => 'FEMENINO',
            'edad_persona' => \Carbon\Carbon::parse('2006-11-20')->age,
            'fecha_nacimiento_persona' => '2006-11-20',
            'email_persona' => 'nohelysq2006@gmail.com',
            'semestre_persona' => '4to SEMESTRE',
            'id_perfil' => 2,
            'id_sede' => 1,
        ]);

        DB::table('persona_pnf')->insert([
            'id_persona' => 3,
            'id_pnf' => 1,
            'fecha_inicio' => now()->toDateString(),
            'fecha_fin' => now()->toDateString(),
        ]);
    }
}
