<?php

namespace App\Http\Controllers\salud;

use App\Models\salud\CategoriaMedicamento;
use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class CategoriaMedicamentoController extends Controller
{

    private const TIPO_PRODUCTO_ID = 2;

    public function index(Request $request)
    {
        $categorias = Categoria::listarCategorias(
            $request->input('buscar'),
            $request->input('activo', 1),
            self::TIPO_PRODUCTO_ID
        );

        return view('admin.salud.maestros.categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                'unique:categorias,nombre'
            ],
            'descripcion' => 'nullable|string',
        ], [
            'nombre.unique' => 'Ya existe una categoria con este nombre',
        ]);

        Categoria::crearCategoria(
            $validated,
            self::TIPO_PRODUCTO_ID
        );

        return redirect()->to($request->input('from', route('admin.salud.maestros.categorias.index')))
            ->with('exito', 'Categoría registrada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
        ]);

        Categoria::actualizarCategoria($id, $request->only(['nombre', 'descripcion']));

        return redirect()->to($request->input('from', route('admin.salud.maestros.categorias.index')))
            ->with('exito', 'Categoría actualizada correctamente.');
    }



    public function destroy($id)
    {
        if (Categoria::tieneProductos($id)) {
            $cantidad = Categoria::contarProductos($id);

            return redirect()
                ->route('admin.salud.maestros.categorias.index')
                ->with('error', "No se puede eliminar la categoría porque tiene {$cantidad} producto(s) asociado(s).")
                ->with('icono', 'error');
        }

        Categoria::eliminarCategoria($id);

        return redirect()
            ->route('admin.salud.maestros.categorias.index')
            ->with('success', 'Categoría eliminada exitosamente.');
    }

    public function activar($id)
    {
        Categoria::activarCategoria($id);
        return redirect()->route('admin.salud.maestros.categorias.index')->with('success', 'Categoria activada exitosamente.');
    }
}
