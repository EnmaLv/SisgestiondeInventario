<?php

namespace App\Models\salud;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EstadoAnimo
{
    public static function buscarYPaginar($busqueda = null, $porPagina = 10)
    {
        $query = DB::table('estado_animos')->where('status', 1);

        if (!empty($busqueda)) {
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'like', '%' . $busqueda . '%')
                  ->orWhere('valor', 'like', '%' . $busqueda . '%');
            });
        }

        return $query->orderBy('valor', 'asc')->paginate($porPagina);
    }

    public static function valoresDisponibles($excluirId = null)
    {
        $query = DB::table('estado_animos')
            ->where('status', 1);
            
        if ($excluirId) {
            $query->where('id', '!=', $excluirId);
        }
        $ocupados = $query->pluck('valor')->toArray();
        $disponibles = [];
        for ($i = 1; $i <= 10; $i++) {
            if (!in_array($i, $ocupados)) {
                $disponibles[] = $i;
            }
        }
        return $disponibles;
    }

    public static function nombreExiste($nombre)
    {
        return DB::table('estado_animos')
            ->where('status', 1)
            ->whereRaw('LOWER(nombre) = ?', [strtolower($nombre)])
            ->exists();
    }

    public static function crear($datos)
    {
        return DB::table('estado_animos')->insert([
            'nombre' => $datos['nombre'],
            'valor' => $datos['valor'],
            'created_at' => now(),

        ]);
    }

    public static function obtenerPorId($id)
    {
        return DB::table('estado_animos')
            ->where('status', 1)
            ->where('id', $id)
            ->first();
    }

    public static function enUsoUltimaNota($id)
    {
        $userIds = DB::table('citas')
            ->where('estado_animo_id', $id)
            ->where('estado', 'realizada')
            ->where('status', 1)
            ->pluck('user_id')
            ->unique();
            
        foreach ($userIds as $userId) {
            $latestCita = DB::table('citas')
                ->where('user_id', $userId)
                ->where('estado', 'realizada')
                ->where('status', 1)
                ->orderBy('fecha', 'desc')
                ->orderBy('hora', 'desc')
                ->first();
                
            if ($latestCita && $latestCita->estado_animo_id == $id) {
                return true;
            }
        }
        
        return false;
    }

    public static function eliminar($id)
    {
        if (self::enUsoUltimaNota($id)) {
            throw new \Exception('No se puede eliminar este estado de ánimo porque está registrado en la última nota de evolución de un paciente.');
        }

        return DB::table('estado_animos')
            ->where('id', $id)
            ->update(['status' => 0,
        'update_at' => Carbon::now() ]);
    }

    public static function actualizar($id, $datos)
    {
        if (self::enUsoUltimaNota($id)) {
            throw new \Exception('No se puede editar este estado de ánimo porque está registrado en la última nota de evolución de un paciente.');
        }

        return DB::table('estado_animos')
            ->where('id', $id)
            ->update([
                'nombre' => $datos['nombre'],
                'valor' => $datos['valor'],
            ]);
    }

    public static function obtenerActivos()
    {
        return DB::table('estado_animos')->where('status', 1)->get();
    }

    public static function obtenerNombrePorId($id)
    {
        return DB::table('estado_animos')
            ->where('id', $id)
            ->value('nombre');
    }
}
