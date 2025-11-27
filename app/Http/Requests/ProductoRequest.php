<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'categoria_id' => 'required|exists:categorias,id',
            'codigo' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            // permitir que no venga imagen en creación
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'precio_compra' => 'required|numeric',
            'stock_minimo' => 'required|integer',
            'stock_maximo' => 'required|integer',
            'unidad_id' => 'required|exists:unidades,id',
            // permitir que no venga estado (lo pones como hidden en el formulario o lo haces nullable)
            'estado' => 'nullable|boolean',
        ];
    }

}
