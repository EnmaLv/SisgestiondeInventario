<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BecaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'activo' => ['nullable', 'boolean'],
            'beneficios' => ['nullable', 'array'],
            'beneficios.*.id' => ['nullable', 'exists:be_beneficios,id'],
            'beneficios.*.observacion' => ['nullable', 'string'],
            'beneficios.*.activo' => ['nullable'],
            'requiere_tutor' => ['nullable', 'boolean'],
            'tutores' => ['nullable', 'array'],
            'tutores.*.id' => ['nullable', 'exists:be_beca_tutores,id'],
            'tutores.*.rol_id' => ['nullable', 'exists:rol,id_rol'],
            'tutores.*.tutor_id' => ['nullable', 'exists:persona,id_persona'],
            'tutores.*.descripcion' => ['nullable', 'string'],
            'asignaciones' => ['nullable', 'array'],
            'asignaciones.*.id' => ['nullable', 'exists:be_beca_trabajo_asignaciones,id'],
            'asignaciones.*.area' => ['nullable', 'string', 'max:255'],
            'asignaciones.*.horario' => ['nullable', 'string', 'max:255'],
            'asignaciones.*.tutor_id' => ['nullable', 'exists:persona,id_persona'],
            'asignaciones.*.observaciones' => ['nullable', 'string'],
            'asignaciones.*.activo' => ['nullable', 'boolean'],
            'preguntas' => ['nullable', 'array'],
            'preguntas.*.texto' => ['required', 'string', 'filled'],
            'preguntas.*.tipo' => ['required', 'in:text,number'],
            'preguntas.*.min' => ['nullable', 'numeric'],
            'preguntas.*.max' => ['nullable', 'numeric'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'activo' => $this->boolean('activo'),
            'requiere_tutor' => $this->boolean('requiere_tutor'),
        ]);
    }
}
