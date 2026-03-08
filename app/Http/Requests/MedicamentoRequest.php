<?php

namespace App\Http\Requests;

use App\Models\salud\Medicamento;
use Illuminate\Foundation\Http\FormRequest;

class MedicamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'categoria_medicamento_id' => 'required|exists:categoria_medicamentos,id',
            'codigo' => 'nullable|string|max:255',
            'nombre' => 'required|string|max:255|unique:medicamentos,nombre',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'precio_compra' => 'nullable|numeric',
            'stock_minimo' => 'required|integer',
            'stock_maximo' => 'required|integer',
            'peso_contenido' => 'required|numeric|min:1',
            'unidad_id' => 'required|exists:unidades,id',
            'envase_primario_id' => 'required|exists:envase_primarios,id',
            'estado' => 'nullable|boolean',
            'costo_usd' => 'sometimes|required|numeric|min:0'
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.unique' => 'Ya existe un medicamento con este nombre',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $nombre = $this->input('nombre');
            $id = $this->route('producto');

            if ($this->isMethod('post')) {
                $exists = Medicamento::where('nombre', $nombre)->exists();
                if ($exists) {
                    $validator->errors()->add('nombre', 'Ya existe un producto con este nombre');
                }
            } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
                $exists = Medicamento::where('nombre', $nombre)->where('id', '!=', $id)->exists();
                if ($exists) {
                    $validator->errors()->add('nombre', 'Ya existe un producto con este nombre');
                }
            }
        });
    }
}
