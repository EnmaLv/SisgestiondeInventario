<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class DefaultRolesSeeder extends Seeder
{
    public function run()
    {
        // Administrador: tiene acceso completo (verificado por nombre de rol en filtros)
        Rol::updateOrCreate(
            ['nombre' => 'Administrador'],
            [
                'descripcion' => 'Rol por defecto Administrador',
                'menu_permissions' => [],
            ]
        );

        // Obrero: permisos limitados para registro de comida
        Rol::updateOrCreate(
            ['nombre' => 'Obrero'],
            [
                'descripcion' => 'Rol por defecto Obrero',
                'menu_permissions' => [
                    'registro_comida',
                    'registro_diario',
                ],
            ]
        );
    }
}
