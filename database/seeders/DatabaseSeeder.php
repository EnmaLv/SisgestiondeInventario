<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EstatusSeeder::class,
            UnidadSeeder::class,
            CategoriaSeeder::class,
            SucursalSeeder::class,
            RecetaSeeder::class,
            EstadoSeeder::class,
            MunicipioSeeder::class,
            LocalidadSeeder::class,
            PerfilSeeder::class,
            PnfSeeder::class,
            SedeSeeder::class,
            PersonaSeeder::class,
            ProductoSeeder::class,
            RecetaIngredienteSeeder::class,
            PrecioProductoSeeder::class,
            EnvasePrimarioSeeder::class,
        ]);
        \App\Models\Proveedor::factory(1)->create();
    }
}
