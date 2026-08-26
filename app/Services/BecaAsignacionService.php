<?php

namespace App\Services;

use App\Models\Becas\Beca;

class BecaAsignacionService
{
    public function sincronizar(Beca $beca, array $asignaciones): void
    {
        $guardados = [];

        foreach ($asignaciones as $asignacion) {
            if (empty($asignacion['area'])
                && empty($asignacion['horario'])
                && empty($asignacion['tutor_id'])
                && empty($asignacion['observaciones'])) {
                continue;
            }

            $data = [
                'area' => $asignacion['area'] ?? null,
                'horario' => $asignacion['horario'] ?? null,
                'tutor_id' => $asignacion['tutor_id'] ?: null,
                'observaciones' => $asignacion['observaciones'] ?? null,
                'activo' => isset($asignacion['activo']) && $asignacion['activo'] ? 1 : 0,
            ];

            if (!empty($asignacion['id']) && $beca->asignacionesTrabajo()->where('id', $asignacion['id'])->exists()) {
                $beca->asignacionesTrabajo()->where('id', $asignacion['id'])->update($data);
                $guardados[] = $asignacion['id'];
            } else {
                $nueva = $beca->asignacionesTrabajo()->create($data);
                $guardados[] = $nueva->id;
            }
        }

        if (!empty($guardados)) {
            $beca->asignacionesTrabajo()->whereNotIn('id', $guardados)->delete();
        } else {
            $beca->asignacionesTrabajo()->delete();
        }
    }
}
