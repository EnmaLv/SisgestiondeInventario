<?php

namespace App\Services;

use App\Models\Becas\Beneficio;
use App\Models\Persona;
use App\Models\Rol;

class BecaFormService
{
    public function datosFormulario(): array
    {
        return [
            'beneficios' => Beneficio::where('status', true)
                ->orderBy('nombre_beneficio')
                ->get(),
            'tutores' => Persona::whereHas('usuarios', function ($q) {
                    $q->whereHas('roles');
                })
                ->with(['usuarios.roles'])
                ->orderBy('nombre_persona')
                ->get(),
            'roles' => Rol::orderBy('nombre')->get(),
            'estudiantes' => Persona::where('estado', true)
                ->whereHas('perfil', function ($query) {
                    $query->where('nombre_perfil', 'Estudiante');
                })
                ->orderBy('nombre_persona')
                ->get(),
        ];
    }
}
