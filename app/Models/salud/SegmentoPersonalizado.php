<?php

namespace App\Models\salud;

use Illuminate\Support\Facades\DB;

class SegmentoPersonalizado
{
    public static function obtenerSeccion($seccionId)
    {
        return DB::table('historia_secciones_personalizadas')->where('id', $seccionId)->first();
    }

    public static function actualizarSegmentosExtra($segmentosExtra)
    {
        if (empty($segmentosExtra) || !is_array($segmentosExtra)) return;

        try {
            DB::beginTransaction();
            foreach ($segmentosExtra as $segmentoId => $contenido) {
                if ($contenido !== null) {
                    $encrypted = \Illuminate\Support\Facades\Crypt::encryptString($contenido);
                    DB::table('historia_segmentos_personalizados')
                        ->where('id', $segmentoId)
                        ->update(['contenido' => $encrypted, 'updated_at' => now()]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function actualizarMetadata($metadata)
    {
        if (empty($metadata) || !is_array($metadata)) return;

        try {
            DB::beginTransaction();
            foreach ($metadata as $segmentoId => $meta) {
                DB::table('historia_segmentos_personalizados')
                    ->where('id', $segmentoId)
                    ->update(['titulo' => $meta['titulo'] ?? null, 'updated_at' => now()]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
