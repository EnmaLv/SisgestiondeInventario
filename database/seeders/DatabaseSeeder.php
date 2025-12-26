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


        $this->call([
            EstatusSeeder::class,
            EstadoVeSeeder::class,
            UnidadSeeder::class,
            CategoriaSeeder::class,
            SucursalSeeder::class,
            RecetaSeeder::class,
            //Seeders con relacion
            PerfilSeeder::class,
            PnfSeeder::class,
            SedeSeeder::class,
            PersonaSeeder::class,
            ProductoSeeder::class,
            RecetaIngredienteSeeder::class,
            /* PrecioProductoSeeder::class, */
        ]);

        \App\Models\Proveedor::factory(1)->create();


        User::create([
            'name' => 'Enma',
            'email' => 'medina.enma1234@gmail.com',
            'password' => bcrypt('31008661'),
        ]);
    }
}
