<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AvanceSesionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $avancesPredeterminados = [
            ['nombre' => 'Estancado / Sin cambios', 'descripcion' => 'No se observan mejoras clínicas evidentes, el paciente se mantiene en el mismo estado.', 'valor' => 1],
            ['nombre' => 'En progreso / Mejora leve', 'descripcion' => 'Se observan mejoras leves y progresivas en el estado del paciente.', 'valor' => 3],
            ['nombre' => 'Logrado / Mejora significativa', 'descripcion' => 'El paciente ha alcanzado el objetivo terapéutico con mejoras significativas.', 'valor' => 5],
        ];

        foreach ($avancesPredeterminados as $avance) {
            $existe = DB::table('avances_sesion')
                ->where('nombre', $avance['nombre'])
                ->where('es_sistema', true)
                ->exists();

            if (!$existe) {
                DB::table('avances_sesion')->insert([
                    'psicologo_id' => null,
                    'nombre' => $avance['nombre'],
                    'descripcion' => $avance['descripcion'],
                    'valor' => $avance['valor'],
                    'estado' => true,
                    'es_sistema' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
