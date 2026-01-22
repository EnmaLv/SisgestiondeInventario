<?php

namespace App\Http\Requests;

use App\Models\Producto;
use Illuminate\Foundation\Http\FormRequest;

class ProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'categoria_id' => 'required|exists:categorias,id',
            'codigo' => 'nullable|string|max:255',
            'nombre' => 'required|string|max:255|unique:productos,nombre',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'precio_compra' => 'nullable|numeric',
            'stock_minimo' => 'required|integer',
            'stock_maximo' => 'required|integer',
            'peso_contenido' => 'required|numeric|min:1',
            'unidad_id' => 'required|exists:unidades,id',
            'estado' => 'nullable|boolean',
            'costo_usd' => 'sometimes|required|numeric|min:0'
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.unique' => 'Ya existe un producto con este nombre',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $nombre = $this->input('nombre');
            $id = $this->route('producto');

            if ($this->isMethod('post')) {
                $exists = Producto::where('nombre', $nombre)->exists();
                if ($exists) {
                    $validator->errors()->add('nombre', 'Ya existe un producto con este nombre');
                }
            } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
                $exists = Producto::where('nombre', $nombre)->where('id', '!=', $id)->exists();
                if ($exists) {
                    $validator->errors()->add('nombre', 'Ya existe un producto con este nombre');
                }
            }
        });
    }
}
