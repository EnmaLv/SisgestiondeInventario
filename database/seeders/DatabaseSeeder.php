<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        \App\Models\Sucursal::factory(10)->create();
        \App\Models\Categoria::factory(10)->create();
        \App\Models\Producto::factory(5)->create();
        \App\Models\Proveedor::factory(10)->create();
        $this->call([
            EstatusSeeder::class,
            EstadoVeSeeder::class,
            //Seeders con relacion
            PerfilSeeder::class,
            PnfSeeder::class,
            SedeSeeder::class,
            PersonaSeeder::class,
        ]);

        User::create([
            'name' => 'Enma',
            'email' => 'medina.enma1234@gmail.com',
            'password' => bcrypt('31008661'),
        ]);

    }
}
