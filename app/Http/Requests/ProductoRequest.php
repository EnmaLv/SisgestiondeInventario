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
        //Para que la imagen sea obligatoria solo en el metodo post
        $imageRule = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        if ($this->isMethod('post')) {
            $imageRule = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        return [
            'categoria_id' => 'required|exists:categorias,id',
            'codigo' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'imagen' => $imageRule,
            'precio_compra' => 'required|numeric',
            'precio_venta' => 'required|numeric',
            'stock_minimo' => 'required|integer',
            'stock_maximo' => 'required|integer',
            'unidad_medida' => 'required',
            'estado' => 'required|boolean',
        ];
    }
}
