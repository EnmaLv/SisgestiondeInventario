<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Http\Requests\CategoriaRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $categorias = Categoria::listarCategorias(
            $request->input('buscar'),
            $request->input('estado', 1)
        );

        return view('admin.maestros.categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('admin.maestros.categorias.create');
    }

    public function store(CategoriaRequest $request)
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
        $fromidreuse = Categoria::crearCategoria($validated);

        $from = $request->input('from');

        if ($from) {
            return redirect($from . '?categoria_id=' . $fromidreuse)
                ->with('success', 'Categoría creada exitosamente.');
        } else {
            redirect()->route('admin.maestros.categorias.index')
                ->with('success', 'Categoría creada exitosamente.');
        }
    }

    public function show($id)
    {
        $categoria = Categoria::obtenerCategoriaConProductos($id);

        if (!$categoria) {
            return redirect()
                ->route('admin.maestros.categorias.index')
                ->with('mensaje', 'Categoría no encontrada.')
                ->with('icono', 'error');
        }

        return view('admin.maestros.categorias.show', compact('categoria'));
    }

    public function edit($id)
    {
        $categoria = Categoria::obtenerCategoria($id);

        if (!$categoria) {
            return redirect()
                ->route('admin.maestros.categorias.index')
                ->with('mensaje', 'Categoría no encontrada.')
                ->with('icono', 'error');
        }

        return view('admin.maestros.categorias.edit', compact('categoria'));
    }
    public function update(CategoriaRequest $request, $id)
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categorias', 'nombre')->ignore($id),
            ],
            'descripcion' => 'nullable|string',
        ], [
            'nombre.unique' => 'Ya existe una categoria con este nombre',
        ]);
        Categoria::actualizarCategoria($id, $validated);

        return redirect()
            ->route('admin.maestros.categorias.index')->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroy($id)
    {
        if (Categoria::tieneProductos($id)) {
            $cantidad = Categoria::contarProductos($id);

            return redirect()
                ->route('admin.maestros.categorias.index')
                ->with('error', "No se puede eliminar la categoría porque tiene {$cantidad} producto(s) asociado(s).")
                ->with('icono', 'error');
        }

        Categoria::eliminarCategoria($id);

        return redirect()
            ->route('admin.maestros.categorias.index')
            ->with('success', 'Categoría eliminada exitosamente.');
    }

    public function activar($id)
    {
        Categoria::activarCategoria($id);
        return redirect()->route('admin.maestros.categorias.index')->with('success', 'Categoria activada exitosamente.');
    }
}
