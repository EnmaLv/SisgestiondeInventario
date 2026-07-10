<?php

namespace App\Services;

use App\Models\Becas\Beca;

class BecaTutorService
{
    public function sincronizar(Beca $beca, array $tutores): void
    {
        $guardados = [];

        foreach ($tutores as $tutor) {
            if (empty($tutor['tutor_id'])) {
                continue;
            }

            $data = [
                'tutor_id' => $tutor['tutor_id'],
                'rol_id' => $tutor['rol_id'] ?? null,
                'descripcion' => $tutor['descripcion'] ?? null,
            ];

            if (!empty($tutor['id']) && $beca->tutores()->where('id', $tutor['id'])->exists()) {
                $beca->tutores()->where('id', $tutor['id'])->update($data);
                $guardados[] = $tutor['id'];
            } else {
                $nuevo = $beca->tutores()->create($data);
                $guardados[] = $nuevo->id;
            }
        }

        if (!empty($guardados)) {
            $beca->tutores()->whereNotIn('id', $guardados)->delete();
        } else {
            $beca->tutores()->delete();
        }
    }
}
