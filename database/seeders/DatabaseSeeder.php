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
        \App\Models\Sucursal::factory(20)->create();
        \App\Models\Categoria::factory(50)->create();
        \App\Models\Producto::factory(200)->create();

        User::create([
            'name' => 'Enma',
            'email' => 'medina.enma1234@gmail.com',
            'password' => bcrypt('31008661'),
        ]);
    }
}
