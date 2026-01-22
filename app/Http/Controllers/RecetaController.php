<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RecetaController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $estado = $request->input('estado', 1);

        $query = Receta::query();

        if ($buscar) {
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%");
            });
        }

        if ($estado !== null && $estado !== '') {
            $query->where('estado', (int)$estado);
        } else {
            $query->where('estado', 1);
        }

        $recetas = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.maestros.recetas.index', compact('recetas', 'buscar', 'estado'));
    }

    public function create()
    {
        return view('admin.maestros.recetas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                'unique:recetas,nombre'
            ],
            'descripcion' => 'nullable|string',
        ], [
            'nombre.unique' => 'Ya existe una receta con este nombre',
        ]);

        $receta = new Receta();
        $receta->nombre = $validated['nombre'];
        $receta->descripcion = $validated['descripcion'];
        $receta->estado = true;
        $receta->save();

        return redirect()
            ->route('admin.maestros.recetas.index')
            ->with('success', 'Receta creada exitosamente.');
    }


    public function edit($id)
    {
        $receta = Receta::findOrFail($id);
        return view('admin.maestros.recetas.edit', compact('receta'));
    }

    public function update(Request $request, Receta $receta)
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('recetas', 'nombre')->ignore($receta->id),
            ],
            'descripcion' => 'nullable|string',
        ], [
            'nombre.unique' => 'Ya existe una receta con este nombre',
        ]);

        $receta->nombre = $validated['nombre'];
        $receta->descripcion = $validated['descripcion'];
        $receta->save();

        return redirect()
            ->route('admin.maestros.recetas.index')
            ->with('success', 'Receta actualizada exitosamente.');
    }


    public function destroy($id)
    {
        if (Receta::tieneIngredienes($id)) {

            $cantidad = Receta::cantidadIngredientes($id);

            return redirect()
                ->route('admin.maestros.recetas.index')
                ->with(
                    'error',
                    "No se puede eliminar la receta porque tiene " . round($cantidad) . " ingrediente(s) asociado(s)."
                )
                ->with('icono', 'error');
        }
        Receta::eliminarReceta($id);
        return redirect()->route('admin.maestros.recetas.index')->with('success', 'Receta eliminada exitosamente.');
    }

    public function activar($id)
    {
        Receta::activarReceta($id);
        return redirect()->route('admin.maestros.recetas.index')->with('success', 'Categoria activada exitosamente.');
    }
}
