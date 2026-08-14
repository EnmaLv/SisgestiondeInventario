<?php

namespace App\Models\salud;

use Illuminate\Support\Facades\DB;

class AvanceSesion
{
    /**
     * Obtener todos los avances de un psicólogo.
     */
    public static function obtenerPorPsicologo($psicologoId)
    {
        return DB::table('avances_sesion')
            ->where(function ($query) use ($psicologoId) {
                $query->where('psicologo_id', $psicologoId)
                    ->orWhere('es_sistema', true);
            })
            ->where('status', 1)
            ->orderBy('nombre', 'asc')
            ->get();
    }

    /**
     * Obtener avances paginados y con búsqueda.
     */
    public static function obtenerPaginadoPorPsicologo($psicologoId, $search = null, $perPage = 10)
    {
        $query = DB::table('avances_sesion')
            ->where(function ($q) use ($psicologoId) {
                $q->where('psicologo_id', $psicologoId)
                    ->orWhere('es_sistema', true);
            })
            ->where('status', 1);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', '%' . $search . '%')
                    ->orWhere('valor', 'like', '%' . $search . '%');
            });
        }
        return $query->orderBy('es_sistema', 'desc')
            ->orderBy('nombre', 'asc')
            ->paginate($perPage);
    }

    /**
     * Crear un nuevo avance.
     */
    public static function crear($psicologoId, $datos)
    {
        // Verificar si ya existe un avance con ese nombre para este psicólogo
        $existe = DB::table('avances_sesion')
            ->where('psicologo_id', $psicologoId)
            ->where('nombre', $datos['nombre'])
            ->where('status', 1)
            ->exists();

        if ($existe) {
            throw new \Exception('Ya existe un avance con este nombre.');
        }

        // Verificar si ya existe un avance con el mismo valor para este psicologo
        $existeValor = DB::table('avances_sesion')
            ->where(function ($q) use ($psicologoId) {
                $q->where('psicologo_id', $psicologoId)
                    ->orWhere('es_sistema', true);
            })
            ->where('valor', $datos['valor'])
            ->where('status', 1)
            ->exists();

        if ($existeValor) {
            throw new \Exception('Ya existe un avance con este valor numérico.');
        }

        return DB::table('avances_sesion')->insertGetId([
            'psicologo_id' => $psicologoId,
            'nombre' => $datos['nombre'],
            'valor' => $datos['valor'],
            'descripcion' => $datos['descripcion'] ?? null,
            'estado' => $datos['estado'] ?? true,
            'created_at' => now(),
        ]);
    }

    /**
     * Actualizar un avance existente.
     */
    public static function actualizar($id, $psicologoId, $datos)
    {
        // Verificar si es sistema
        $avance = DB::table('avances_sesion')->where('id', $id)->first();
        if (!$avance) throw new \Exception('Avance no encontrado.');
        if ($avance->es_sistema) {
            throw new \Exception('No se puede modificar un avance del sistema.');
        }

        if (self::enUsoUltimaNota($id, $psicologoId)) {
            throw new \Exception('No se puede editar este avance de sesión porque está registrado en la última nota de evolución de un paciente.');
        }

        // Verificar si otro avance tiene el mismo nombre
        $existe = DB::table('avances_sesion')
            ->where(function ($q) use ($psicologoId) {
                $q->where('psicologo_id', $psicologoId)
                    ->orWhere('es_sistema', true);
            })
            ->where('nombre', $datos['nombre'])
            ->where('id', '!=', $id)
            ->where('status', 1)
            ->exists();

        if ($existe) {
            throw new \Exception('Ya existe otro avance con este nombre.');
        }

        // Verificar si otro avance tiene el mismo valor
        $existeValor = DB::table('avances_sesion')
            ->where(function ($q) use ($psicologoId) {
                $q->where('psicologo_id', $psicologoId)
                    ->orWhere('es_sistema', true);
            })
            ->where('valor', $datos['valor'])
            ->where('id', '!=', $id)
            ->where('status', 1)
            ->exists();

        if ($existeValor) {
            throw new \Exception('Ya existe otro avance con este valor numérico.');
        }

        return DB::table('avances_sesion')
            ->where('id', $id)
            ->where('psicologo_id', $psicologoId)
            ->update([
                'nombre' => $datos['nombre'],
                'valor' => $datos['valor'],
                'descripcion' => $datos['descripcion'] ?? null,
                'estado' => $datos['estado'] ?? true,
                'updated_at' => now(),
            ]);
    }

    /**
     * Eliminar (soft delete) un avance.
     */
    public static function eliminar($id, $psicologoId)
    {
        $avance = DB::table('avances_sesion')->where('id', $id)->first();
        if (!$avance) throw new \Exception('Avance no encontrado.');
        if ($avance->es_sistema) {
            throw new \Exception('No se puede eliminar un avance del sistema.');
        }

        if (self::enUsoUltimaNota($id, $psicologoId)) {
            throw new \Exception('No se puede eliminar este avance de sesión porque está registrado en la última nota de evolución de un paciente.');
        }

        return DB::table('avances_sesion')
            ->where('id', $id)
            ->where('psicologo_id', $psicologoId)
            ->update([
                'status' => 0,
                'updated_at' => now(),
            ]);
    }

    public static function enUsoUltimaNota($id, $psicologoId = null)
    {
        $query = DB::table('citas')
            ->where('estado', 'realizada')
            ->where('status', 1)
            ->where('notas', 'like', '%"avance_estado":"' . $id . '"%');

        if ($psicologoId) {
            $query->where('psicologo_id', $psicologoId);
        }

        $userIds = $query->pluck('user_id')->unique();

        foreach ($userIds as $userId) {
            $latestQuery = DB::table('citas')
                ->where('user_id', $userId)
                ->where('estado', 'realizada')
                ->where('status', 1)
                ->orderBy('fecha', 'desc')
                ->orderBy('hora', 'desc');

            if ($psicologoId) {
                $latestQuery->where('psicologo_id', $psicologoId);
            }

            $latestCita = $latestQuery->first();

            if ($latestCita && $latestCita->notas) {
                $notas = json_decode($latestCita->notas, true);
                if (isset($notas['avance_estado']) && $notas['avance_estado'] == $id) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function obtenerNombrePorId($id)
    {
        return DB::table('avances_sesion')
            ->where('id', $id)
            ->value('nombre');
    }
}
