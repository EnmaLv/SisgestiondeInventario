<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Http\Requests\CategoriaRequest;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categorias = Categoria::listarCategorias(
            $request->input('buscar'),
            $request->input('estado')
        );

        return view('admin.maestros.categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.maestros.categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoriaRequest $request)
    {
        $validated = $request->validated();

        Categoria::crearCategoria($validated);

        return redirect()
            ->route('admin.maestros.categorias.index')
            ->with('mensaje', 'Categoría creada exitosamente.')
            ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
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

    /**
     * Show the form for editing the specified resource.
     */
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

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoriaRequest $request, $id)
    {
        $validated = $request->validated();

        Categoria::actualizarCategoria($id, $validated);

        return redirect()
            ->route('admin.maestros.categorias.index')
            ->with('mensaje', 'Categoría actualizada exitosamente.')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Verificar si tiene productos antes de eliminar
        if (Categoria::tieneProductos($id)) {
            $cantidad = Categoria::contarProductos($id);
            
            return redirect()
                ->route('admin.maestros.categorias.index')
                ->with('mensaje', "No se puede eliminar la categoría porque tiene {$cantidad} producto(s) asociado(s).")
                ->with('icono', 'error');
        }

        Categoria::eliminarCategoria($id);

        return redirect()
            ->route('admin.maestros.categorias.index')
            ->with('mensaje', 'Categoría eliminada exitosamente.')
            ->with('icono', 'success');
    }
}