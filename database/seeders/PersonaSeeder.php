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

        DB::table('persona')->insert([
            'nombre_persona' => 'Enmanuel ',
            'segundo_nombre_persona' => 'Jesus',
            'apellido_persona' => 'Medina',
            'segundo_apellido_persona' => 'Barros',
            'cedula_persona' => '31008661',
            'telefono_persona' => '12345678',
            'genero_persona' => 'Masculino',
            'edad_persona' => 20,
            'fecha_nacimiento_persona' => '2000-01-01',
            'email_persona' => 'medina1234@gmail.com',
            'id_perfil' => 1,
            'id_sede' => 1,
        ]);

        DB::table('persona_pnf')->insert([
            'id_persona' => 2,
            'id_pnf' => 1,
            'fecha_inicio' => '2000-01-01',
            'fecha_fin' => '2000-01-01',
        ]);


        DB::table('persona')->insert([
            'nombre_persona' => 'Abdias ',
            'segundo_nombre_persona' => 'Samuel',
            'apellido_persona' => 'Campos',
            'segundo_apellido_persona' => 'Nose',
            'cedula_persona' => '30133077',
            'telefono_persona' => '12345678',
            'genero_persona' => 'Masculino',
            'edad_persona' => 20,
            'fecha_nacimiento_persona' => '2000-01-01',
            'email_persona' => 'abdias.scc@gmail.com',
            'id_perfil' => 1,
            'id_sede' => 1,
        ]);

        DB::table('persona_pnf')->insert([
            'id_persona' => 3,
            'id_pnf' => 1,
            'fecha_inicio' => '2000-01-01',
            'fecha_fin' => '2000-01-01',
        ]);

        DB::table('persona')->insert([
            'nombre_persona' => 'Angel ',
            'segundo_nombre_persona' => 'Jesus',
            'apellido_persona' => 'Linarez',
            'segundo_apellido_persona' => 'Nose',
            'cedula_persona' => '31216160',
            'telefono_persona' => '12345678',
            'genero_persona' => 'Masculino',
            'edad_persona' => 20,
            'fecha_nacimiento_persona' => '2000-01-01',
            'email_persona' => 'AngelLinarez@gmail.com',
            'id_perfil' => 1,
            'id_sede' => 1,
        ]);

        DB::table('persona_pnf')->insert([
            'id_persona' => 4,
            'id_pnf' => 1,
            'fecha_inicio' => '2000-01-01',
            'fecha_fin' => '2000-01-01',
        ]);

        DB::table('persona')->insert([
            'nombre_persona' => 'Michele ',
            'segundo_nombre_persona' => 'Nose',
            'apellido_persona' => 'Piñuela',
            'segundo_apellido_persona' => 'Nose',
            'cedula_persona' => '11223344',
            'telefono_persona' => '12345678',
            'genero_persona' => 'Masculino',
            'edad_persona' => 20,
            'fecha_nacimiento_persona' => '2000-01-01',
            'email_persona' => 'medina1234@gmail.com',
            'id_perfil' => 1,
            'id_sede' => 1,
        ]);

        DB::table('persona_pnf')->insert([
            'id_persona' => 5,
            'id_pnf' => 1,
            'fecha_inicio' => '2000-01-01',
            'fecha_fin' => '2000-01-01',
        ]);

        DB::table('persona')->insert([
            'nombre_persona' => 'Deiby',
            'segundo_nombre_persona' => 'Nose',
            'apellido_persona' => 'De Armas',
            'segundo_apellido_persona' => 'Nose',
            'cedula_persona' => '12345679',
            'telefono_persona' => '12345678',
            'genero_persona' => 'Masculino',
            'edad_persona' => 20,
            'fecha_nacimiento_persona' => '2000-01-01',
            'email_persona' => 'medina1234@gmail.com',
            'id_perfil' => 1,
            'id_sede' => 1,
        ]);

        DB::table('persona_pnf')->insert([
            'id_persona' => 6,
            'id_pnf' => 1,
            'fecha_inicio' => '2000-01-01',
            'fecha_fin' => '2000-01-01',
        ]);

        DB::table('persona')->insert([
            'nombre_persona' => 'Nohely',
            'segundo_nombre_persona' => 'Roxana',
            'apellido_persona' => 'Sosa',
            'segundo_apellido_persona' => 'Quintero',
            'cedula_persona' => '31710990',
            'telefono_persona' => '12345678',
            'genero_persona' => 'Femenino',
            'edad_persona' => 19,
            'fecha_nacimiento_persona' => '2000-01-01',
            'email_persona' => 'medina1234@gmail.com',
            'id_perfil' => 1,
            'id_sede' => 1,
        ]);

        DB::table('persona_pnf')->insert([
            'id_persona' => 7,
            'id_pnf' => 1,
            'fecha_inicio' => '2000-01-01',
            'fecha_fin' => '2000-01-01',
        ]);
    }
}
