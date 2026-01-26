<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proveedor>
 */
class ProveedorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'empresa' => "Hiper Sol",
            'nombre' => "Orianna",
            'direccion' => "Prados del Sol Acarigua",
            'telefono' => "0424-5345568",
            'email' => "oriana@gmail.com",
            'estado' => 1,
        ];
    }
}
