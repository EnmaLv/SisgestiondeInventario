<?php

namespace App\Models\salud;

use Illuminate\Support\Facades\DB;

class SeccionPersonalizada
{
    public static function obtenerPorId($id)
    {
        return DB::table('historia_secciones_personalizadas')->where('id', $id)->first();
    }

    public static function obtenerHistoriaClinica($historiaClinicaId)
    {
        return DB::table('historia_clinicas')->where('id', $historiaClinicaId)->first();
    }

    public static function obtenerSegmentos($seccionId)
    {
        return DB::table('historia_segmentos_personalizados')
            ->where('seccion_id', $seccionId)
            ->orderBy('orden')
            ->get();
    }

    public static function crear($historia, $data)
    {
        try {
            DB::beginTransaction();

            $seccionId = DB::table('historia_secciones_personalizadas')->insertGetId([
                'historia_clinica_id' => is_object($historia) ? $historia->id : $historia,
                'titulo'              => $data['titulo'],
                'descripcion_general' => $data['descripcion_general'] ?? null,
                'orden'               => DB::table('historia_secciones_personalizadas')->where('historia_clinica_id', is_object($historia) ? $historia->id : $historia)->count() + 1,
                'status'              => 1,
                'created_at'          => now(),
                'updated_at'          => null,
            ]);

            if (!empty($data['segmentos_titulos'])) {
                foreach ($data['segmentos_titulos'] as $index => $titulo) {
                    DB::table('historia_segmentos_personalizados')->insert([
                        'seccion_id' => $seccionId,
                        'titulo' => $titulo,
                        'orden' => $index + 1,
                        'created_at' => now(),
                        'updated_at' => null,
                    ]);
                }
            }

            $psicologoId = \Illuminate\Support\Facades\Auth::id();
            $plantilla = DB::table('historia_plantillas_secciones')
                ->where('psicologo_id', $psicologoId)
                ->where('titulo', $data['titulo'])
                ->first();
                
            if ($plantilla) {
                DB::table('historia_plantillas_secciones')->where('id', $plantilla->id)
                    ->update(['updated_at' => now()]);
            } else {
                DB::table('historia_plantillas_secciones')->insert([
                    'psicologo_id' => $psicologoId,
                    'titulo' => $data['titulo'],
                    'created_at' => now(),
                    'updated_at' => null,
                ]);
            }

            $seccion = DB::table('historia_secciones_personalizadas')->where('id', $seccionId)->first();
            DB::commit();
            return $seccion;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function eliminar($id)
    {
        try {
            DB::beginTransaction();
            $res = DB::table('historia_secciones_personalizadas')
                ->where('id', $id)
                ->update([
                    'status'     => 0,
                    'updated_at' => now(),
                ]);
            DB::commit();
            return $res;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function reordenar($seccionId, $direccion)
    {
        try {
            DB::beginTransaction();

            $seccion = self::obtenerPorId($seccionId);
            if (!$seccion || $seccion->status != 1) {
                DB::rollBack();
                return false;
            }

            $historiaId = $seccion->historia_clinica_id;
            $ordenActual = $seccion->orden;

            if ($direccion === 'up') {
                $otraSeccion = DB::table('historia_secciones_personalizadas')
                    ->where('historia_clinica_id', $historiaId)
                    ->where('status', 1)
                    ->where('orden', '<', $ordenActual)
                    ->orderBy('orden', 'desc')
                    ->first();
            } else {
                $otraSeccion = DB::table('historia_secciones_personalizadas')
                    ->where('historia_clinica_id', $historiaId)
                    ->where('status', 1)
                    ->where('orden', '>', $ordenActual)
                    ->orderBy('orden', 'asc')
                    ->first();
            }

            if ($otraSeccion) {
                DB::table('historia_secciones_personalizadas')
                    ->where('id', $seccion->id)
                    ->update(['orden' => $otraSeccion->orden, 'updated_at' => now()]);

                DB::table('historia_secciones_personalizadas')
                    ->where('id', $otraSeccion->id)
                    ->update(['orden' => $ordenActual, 'updated_at' => now()]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
