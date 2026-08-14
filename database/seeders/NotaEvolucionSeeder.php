<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotaEvolucionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campos = [
            'Motivo de Consulta',
            'Observaciones Clínicas',
            'Intervenciones / Resumen de Sesión',
            'Detalle del Avance',
            'Plan de Tratamiento',
            'Diagnósticos Oficiales',
            'Estado de Ánimo del Paciente',
            'Estado de Evolución',
            'Próxima Cita Recomendada'
        ];

        foreach ($campos as $campo) {
            if (!\Illuminate\Support\Facades\DB::table('nota_evolucion_campos')->where('titulo', $campo)->whereNull('psicologo_id')->exists()) {
                \Illuminate\Support\Facades\DB::table('nota_evolucion_campos')->insert([
                    'psicologo_id' => null, // Global
                    'titulo' => $campo,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
