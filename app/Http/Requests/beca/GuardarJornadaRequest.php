<?php

namespace App\Http\Requests\beca;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class GuardarJornadaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    protected function prepareForValidation()
    {
        // Si no viene en el request (desmarcado), le asignamos 0, de lo contrario 1.
        $this->merge([
            'activa' => $this->has('activa') ? 1 : 0,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre_jornada' => 'required|string|max:255',
            'descripcion_jornada' => 'nullable|string',
            'beneficio_id' => 'required|exists:be_beneficios,id',
            'lapsos_id' => 'required|exists:be_lapsos,id',
            'fecha_inicio_solicitud' => 'required|date',
            'fecha_fin_solicitud' => 'required|date|after_or_equal:fecha_inicio_solicitud',
            'cupos_maximos' => 'required|integer|min:1',
            'activa' => 'required|boolean'
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'nombre_jornada.required' => 'El nombre de la jornada es obligatorio.',
            'beneficio_id.required' => 'Debe seleccionar un beneficio.',
            'lapsos_id.required' => 'Debe seleccionar un lapso académico.',
            'fecha_inicio_solicitud.required' => 'La fecha de inicio es obligatoria.',
            'fecha_fin_solicitud.required' => 'La fecha de fin es obligatoria.',
            'fecha_fin_solicitud.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
            'cupos_maximos.required' => 'El número de cupos máximos es obligatorio.',
            'cupos_maximos.min' => 'El número de cupos máximos debe ser al menos 1.',
        ];
    }
}
