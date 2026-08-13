<?php

namespace App\Models\salud;

use Illuminate\Support\Facades\DB;

class Prioridad
{
    public static function obtenerParaPsicologo($psicologoId = null)
    {
        return DB::table('prioridades')
            ->where('activo', 1)
            ->where(function ($query) use ($psicologoId) {
                $query->whereNull('psicologo_id');
                if ($psicologoId) {
                    $query->orWhere('psicologo_id', $psicologoId);
                }
            })
            ->orderBy('nivel_gravedad', 'desc')
            ->get();
    }

    public static function buscarYPaginar($busqueda = null, $psicologoId = null, $porPagina = 10)
    {
        $query = DB::table('prioridades')
            ->select('prioridades.*')
            ->addSelect(DB::raw('(SELECT COUNT(*) FROM citas WHERE citas.prioridad = prioridades.nombre) as uso_count'))
            ->where('activo', 1)
            ->where(function ($query) use ($psicologoId) {
                $query->whereNull('psicologo_id');
                if ($psicologoId) {
                    $query->orWhere('psicologo_id', $psicologoId);
                }
            });

        if (!empty($busqueda)) {
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'like', '%' . $busqueda . '%')
                  ->orWhere('nivel_gravedad', 'like', '%' . $busqueda . '%');
            });
        }

        return $query->orderBy('nivel_gravedad', 'desc')->paginate($porPagina);
    }
}
