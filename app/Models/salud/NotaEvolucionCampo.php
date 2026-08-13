<?php

namespace App\Models\salud;

use Illuminate\Support\Facades\DB;

class NotaEvolucionCampo
{
    public static function obtenerCamposDisponibles($psicologoId)
    {
        return DB::table('nota_evolucion_campos')
            ->where(function ($query) use ($psicologoId) {
                $query->where('psicologo_id', $psicologoId)
                      ->orWhereNull('psicologo_id');
            })
            ->where('status', 1)
            ->orderBy('id', 'asc')
            ->get();
    }

    public static function obtenerCamposDisponiblesPaginados($psicologoId, $perPage = 9)
    {
        return DB::table('nota_evolucion_campos')
            ->where(function ($query) use ($psicologoId) {
                $query->where('psicologo_id', $psicologoId)
                      ->orWhereNull('psicologo_id');
            })
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    public static function crearPersonalizado($psicologoId, $titulo)
    {
        return DB::table('nota_evolucion_campos')->insertGetId([
            'psicologo_id' => $psicologoId,
            'titulo' => $titulo,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => null,
        ]);
    }

    public static function existeTitulo($psicologoId, $titulo, $excludeId = null)
    {
        $query = DB::table('nota_evolucion_campos')
            ->where(function ($q) use ($psicologoId) {
                $q->where('psicologo_id', $psicologoId)
                  ->orWhereNull('psicologo_id');
            })
            ->where('status', 1)
            ->where('titulo', $titulo);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public static function obtenerPorPsicologo($psicologoId)
    {
        return DB::table('nota_evolucion_campos')
            ->where('psicologo_id', $psicologoId)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get();
    }

    public static function obtenerPorId($id, $psicologoId)
    {
        return DB::table('nota_evolucion_campos')
            ->where('id', $id)
            ->where('psicologo_id', $psicologoId)
            ->where('status', 1)
            ->first();
    }

    public static function actualizar($id, $psicologoId, $titulo)
    {
        return DB::table('nota_evolucion_campos')
            ->where('id', $id)
            ->where('psicologo_id', $psicologoId)
            ->update([
                'titulo' => $titulo,
                'updated_at' => now(),
            ]);
    }

    public static function eliminar($id, $psicologoId)
    {
        return DB::table('nota_evolucion_campos')
            ->where('id', $id)
            ->where('psicologo_id', $psicologoId)
            ->update([
                'status' => 0,
                'updated_at' => now(),
            ]);
    }
}
