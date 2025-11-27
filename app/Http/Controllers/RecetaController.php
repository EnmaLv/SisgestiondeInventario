<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use Illuminate\Http\Request;

class RecetaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $activo = $request->input('estado');

        $query = Receta::query();

        if ($buscar) {
            $query->where(function($q) use ($buscar) {
                
                $q->Where('nombre','like', "%{$buscar}%");
            });
        }

        if ($activo !== null && $activo !== '') {
            $query->where('estado', (int)$activo);
        }

        $recetas = $query->orderBy('id','desc')->paginate(10);

        return view('admin.maestros.recetas.index', compact('recetas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.maestros.recetas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validamos los datos de la solicitud
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $receta = new Receta();
        $receta->nombre = $validated['nombre'];
        $receta->descripcion = $validated['descripcion'];
        $receta->estado = true; //Activo por defecto
        $receta->save();

        return redirect()->route('admin.maestros.recetas.index')->with('mensaje', 'Receta creada exitosamente.')->with('icono', 'success');   
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $receta = Receta::findOrFail($id);
        return view('admin.maestros.recetas.edit', compact('receta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Receta $receta)
    {
        //Validamos los datos de la solicitud
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $receta->nombre = $validated['nombre'];
        $receta->descripcion = $validated['descripcion'];
        $receta->save();

        return redirect()->route('admin.maestros.recetas.index')->with('mensaje', 'Receta actualizada exitosamente.')->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $receta = Receta::findOrFail($id);
        
        $receta->delete();
        return redirect()->route('admin.maestros.recetas.index')->with('mensaje', 'Receta eliminada exitosamente.')->with('icono', 'success');
    }
}
