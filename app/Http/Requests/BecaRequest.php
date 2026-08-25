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
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'activo' => $this->boolean('activo'),
        ]);
    }
}
