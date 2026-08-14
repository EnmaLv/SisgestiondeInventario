<?php

namespace App\Models\salud;

use Illuminate\Support\Facades\DB;

class CitaNotaEvolucion
{
    public static function obtenerPorCita($citaId)
    {
        return DB::table('cita_nota_evolucion')
            ->join('nota_evolucion_campos', 'cita_nota_evolucion.campo_id', '=', 'nota_evolucion_campos.id')
            ->where('cita_nota_evolucion.cita_id', $citaId)
            ->where('nota_evolucion_campos.status', 1)
            ->select('cita_nota_evolucion.*', 'nota_evolucion_campos.titulo')
            ->orderBy('nota_evolucion_campos.id', 'asc')
            ->get();
    }

    public static function guardarCampo($citaId, $campoId, $contenido)
    {
        return DB::table('cita_nota_evolucion')->updateOrInsert(
            ['cita_id' => $citaId, 'campo_id' => $campoId],
            ['contenido' => $contenido, 'updated_at' => now(), 'created_at' => DB::raw('COALESCE(created_at, NOW())')]
        );
    }
}
