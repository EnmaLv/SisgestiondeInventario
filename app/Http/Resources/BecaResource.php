<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BeneficioResource;

class BecaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'activo' => (bool) $this->activo,
            'beneficios' => BeneficioResource::collection($this->whenLoaded('beneficios')),
            'asignaciones' => $this->whenLoaded('asignacionesTrabajo', function () {
                return $this->asignacionesTrabajo->map(function ($asignacion) {
                    return [
                        'id' => $asignacion->id,
                        'area' => $asignacion->area,
                        'horario' => $asignacion->horario,
                        'tutor' => $asignacion->tutor ? [
                            'id' => $asignacion->tutor->id_persona,
                            'nombre' => trim($asignacion->tutor->nombre_persona . ' ' . $asignacion->tutor->apellido_persona),
                            'email' => $asignacion->tutor->email_persona,
                        ] : null,
                        'observaciones' => $asignacion->observaciones,
                        'activo' => (bool) $asignacion->activo,
                    ];
                });
            }),
            'creado_en' => $this->created_at,
            'actualizado_en' => $this->updated_at,
        ];
    }
}
