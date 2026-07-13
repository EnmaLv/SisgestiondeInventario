<?php

namespace App\Http\Resources\becas;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JornadaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre_jornada,
            'descripcion' => $this->descripcion_jornada ?? 'Sin descripción disponible',
            
            // Agrupamos las fechas de solicitud
            'periodo_solicitud' => [
                'inicio' => $this->fecha_inicio_solicitud,
                'fin' => $this->fecha_fin_solicitud,
            ],
            
            // Manejo de los contadores de cupos
            'cupos' => [
                'maximos' => (int) $this->cupos_maximos,
                'asignados' => (int) $this->cupos_asignados,
                'disponibles' => (int) ($this->cupos_maximos - $this->cupos_asignados),
            ],
            
            // Convertimos el tinyint(1) a un booleano real de PHP
            'esta_activa' => (bool) $this->activa,
            
            // Relaciones opcionales (Solo se incluirán si las cargas en el controlador con ->load())
            'beneficio' => $this->whenLoaded('beneficio'), 
            // 'lapso' => $this->whenLoaded('lapso'),

            'creado_el' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
