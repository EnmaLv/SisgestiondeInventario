<?php

namespace App\Http\Controllers\salud;

use App\Models\salud\CategoriaMedicamento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class CategoriaMedicamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categorias = CategoriaMedicamento::listar(
            $request->input('buscar'),
            $request->input('estado', 1)
        );
        return view('admin.salud.maestros.categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.salud.maestros.categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                'unique:categoria_medicamentos,nombre'
            ],
        ], [
            'nombre.unique' => 'Ya existe una categoria con este nombre',
        ]);
        $fromidreuse = CategoriaMedicamento::crear($validated);

        $from = $request->input('from');

        if ($from) {
            return redirect($from . '?categoria_medicamento_id=' . $fromidreuse)
                ->with('success', 'Categoria creada exitosamente.');
        } else {
            return redirect()->route('admin.salud.maestros.categorias.index')
                ->with('success', 'Categoria creada exitosamente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(categoriaMedicamento $categoriaMedicamento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $categoria = CategoriaMedicamento::obtenerDatos($id);

        if (!$categoria) {
            return redirect()
                ->route('admin.salud.maestros.categorias.index')
                ->with('mensaje', 'Categoria no encontrada.')
                ->with('icono', 'error');
        }

        return view('admin.salud.maestros.categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, categoriaMedicamento $categoria)
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categoria_medicamentos', 'nombre')->ignore($categoria->id),
            ],
        ], [
            'nombre.unique' => 'Ya existe una categoria con este nombre',
        ]);
        CategoriaMedicamento::actualizar($categoria->id, $validated);

        return redirect()
            ->route('admin.salud.maestros.categorias.index')->with('success', 'Categoria actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        CategoriaMedicamento::eliminar($id);

        return redirect()
            ->route('admin.salud.maestros.categorias.index')
            ->with('success', 'Categoria eliminada exitosamente.');
    }

    public function activar($id)
    {
        CategoriaMedicamento::activar($id);
        return redirect()->route('admin.salud.maestros.categorias.index')->with('success', 'Categoria activada exitosamente.');
    }
}
