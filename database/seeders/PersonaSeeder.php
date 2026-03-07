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
            'nombre_persona' => 'Administrador',
            'segundo_nombre_persona' => null,
            'apellido_persona' => 'General',
            'segundo_apellido_persona' => null,
            'cedula_persona' => '12345678',
            'telefono_persona' => 04241234567,
            'genero_persona' => '',
            'edad_persona' => \Carbon\Carbon::parse(now()->toDateString())->age,
            'fecha_nacimiento_persona' => now()->toDateString(),
            'email_persona' => 'admin@example.com',
            'semestre_persona' => null,
            'id_perfil' => 1,
            'id_sede' => 1,
        ]);

        DB::table('usuario')->insert([
            'id_persona' => 1,
            'id_perfil' => 1,
            'username' => 'admin@example.com',
            'password' => bcrypt('12345678'),
            'master_key' => bcrypt('masterkey123'),
            'security_questions' => json_encode([
                [
                    'question' => '¿Cuál es el nombre de tu primera mascota?',
                    'answer' => bcrypt('example'),
                ],
                [
                    'question' => '¿Cuál es el nombre de tu madre?',
                    'answer' => bcrypt('example'),
                ],
            ]),

            'extra_permissions' => null,
        ]);

        DB::table('rol_usuario')->insert([
            'id_rol' => 1,
            'id_usuario' => 1,
        ]);

        DB::table('persona')->insert([
            'nombre_persona' => 'Administrador',
            'segundo_nombre_persona' => null,
            'apellido_persona' => 'Salud',
            'segundo_apellido_persona' => null,
            'cedula_persona' => '11223344',
            'telefono_persona' => 04241234567,
            'genero_persona' => '',
            'edad_persona' => \Carbon\Carbon::parse(now()->toDateString())->age,
            'fecha_nacimiento_persona' => now()->toDateString(),
            'email_persona' => 'nohelysq2006@gmail.com',
            'semestre_persona' => null,
            'id_perfil' => 1,
            'id_sede' => 1,
        ]);

        DB::table('usuario')->insert([
            'id_persona' => 2,
            'id_perfil' => 1,
            'username' => 'nohelysq2006@gmail.com',
            'password' => bcrypt('12345678'),
            'master_key' => bcrypt('masterkey123'),
            'security_questions' => null,
            'extra_permissions' => null,
        ]);

        DB::table('rol_usuario')->insert([
            'id_rol' => 4,
            'id_usuario' => 2,
        ]);
    }
}
