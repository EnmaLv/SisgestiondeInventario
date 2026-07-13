<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Becas\Beneficio;
use App\Models\Becas\Lapso;
use App\Models\Becas\JornadaBeca;
use Carbon\Carbon;

class JornadaBecaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear Beneficios
        $beneficios = [
            [
                'nombre_beneficio' => 'Beca Comedor',
                'descripcion' => 'Subsidio completo para el servicio de comedor universitario diario.',
                'slug' => 'beca-comedor',
                'cupones_disponibles' => 300,
                'cupones_ocupados' => 0,
                'status' => true,
            ],
            [
                'nombre_beneficio' => 'Beca Transporte',
                'descripcion' => 'Subsidio para el transporte universitario en las rutas establecidas.',
                'slug' => 'beca-transporte',
                'cupones_disponibles' => 200,
                'cupones_ocupados' => 0,
                'status' => true,
            ],
            [
                'nombre_beneficio' => 'Beca de Ayuda Económica',
                'descripcion' => 'Apoyo monetario mensual para estudiantes con vulnerabilidad socioeconómica.',
                'slug' => 'beca-ayuda-economica',
                'cupones_disponibles' => 100,
                'cupones_ocupados' => 0,
                'status' => true,
            ],
        ];

        foreach ($beneficios as $b) {
            Beneficio::firstOrCreate(['slug' => $b['slug']], $b);
        }

        // 2. Crear Lapsos
        $lapsos = [
            [
                'codigo' => '2026-I',
                'fecha_inicio' => Carbon::create(2026, 1, 15),
                'fecha_fin' => Carbon::create(2026, 7, 15),
                'es_actual' => true,
                'permite_solicitudes' => true,
            ],
            [
                'codigo' => '2026-II',
                'fecha_inicio' => Carbon::create(2026, 9, 15),
                'fecha_fin' => Carbon::create(2027, 2, 15),
                'es_actual' => false,
                'permite_solicitudes' => false,
            ],
        ];

        foreach ($lapsos as $l) {
            Lapso::firstOrCreate(['codigo' => $l['codigo']], $l);
        }

        // Obtener IDs
        $comedor = Beneficio::where('slug', '=', 'beca-comedor', 'and')->first();
        $transporte = Beneficio::where('slug', '=', 'beca-transporte', 'and')->first();
        $ayuda = Beneficio::where('slug', '=', 'beca-ayuda-economica', 'and')->first();

        $lapsoActual = Lapso::where('codigo', '=', '2026-I', 'and')->first();
        $lapsoSiguiente = Lapso::where('codigo', '=', '2026-II', 'and')->first();

        // 3. Crear Jornadas
        $jornadas = [
            [
                'nombre_jornada' => 'Convocatoria Beca Comedor 2026-I',
                'descripcion_jornada' => 'Proceso de postulación y renovación para la beca de comedor durante el lapso académico 2026-I.',
                'beneficio_id' => $comedor->id,
                'lapsos_id' => $lapsoActual->id,
                'fecha_inicio_solicitud' => Carbon::now()->subDays(5),
                'fecha_fin_solicitud' => Carbon::now()->addDays(15),
                'cupos_maximos' => 150,
                'cupos_asignados' => 0,
                'activa' => true,
            ],
            [
                'nombre_jornada' => 'Convocatoria Beca Transporte 2026-I',
                'descripcion_jornada' => 'Proceso de postulación para el beneficio de transporte en las rutas universitarias.',
                'beneficio_id' => $transporte->id,
                'lapsos_id' => $lapsoActual->id,
                'fecha_inicio_solicitud' => Carbon::now()->subDays(5),
                'fecha_fin_solicitud' => Carbon::now()->addDays(10),
                'cupos_maximos' => 100,
                'cupos_asignados' => 0,
                'activa' => true,
            ],
            [
                'nombre_jornada' => 'Convocatoria Ayuda Económica Especial 2026-I',
                'descripcion_jornada' => 'Asignación de ayudas económicas para estudiantes en situaciones excepcionales.',
                'beneficio_id' => $ayuda->id,
                'lapsos_id' => $lapsoActual->id,
                'fecha_inicio_solicitud' => Carbon::now()->subDays(10),
                'fecha_fin_solicitud' => Carbon::now()->subDay(), // Venció ayer
                'cupos_maximos' => 50,
                'cupos_asignados' => 0,
                'activa' => false, // Vencida/inactiva
            ],
        ];

        foreach ($jornadas as $j) {
            JornadaBeca::firstOrCreate(
                ['nombre_jornada' => $j['nombre_jornada']],
                $j
            );
        }
    }
}
