<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sucursales = Sucursal::all();
        return view('admin.maestros.sucursales.index', compact('sucursales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.maestros.sucursales.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'activo' => 'required|boolean',
        ]);

        $sucursal = new Sucursal();
        $sucursal->nombre = $validated['nombre'];
        $sucursal->direccion = $validated['direccion'];
        $sucursal->telefono = $validated['telefono'];
        $sucursal->activo = $validated['activo'];
        $sucursal->save();

        return redirect()->route('admin.maestros.sucursales.index')->with('success', 'Sucursal creada exitosamente.')->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $sucursal = Sucursal::findOrFail($id);
        return view('admin.maestros.sucursales.show', compact('sucursal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $sucursal = Sucursal::findOrFail($id);
        return view('admin.maestros.sucursales.edit', compact('sucursal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $sucursal = Sucursal::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'activo' => 'required|boolean',
        ]);

        $sucursal->nombre = $validated['nombre'];
        $sucursal->direccion = $validated['direccion'];
        $sucursal->telefono = $validated['telefono'];
        $sucursal->activo = $validated['activo'];
        $sucursal->save();

        return redirect()->route('admin.maestros.sucursales.index')->with('success', 'Sucursal actualizada exitosamente.')->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sucursal = Sucursal::findOrFail($id);
        $sucursal->delete();

        return redirect()->route('admin.maestros.sucursales.index')->with('success', 'Sucursal eliminada exitosamente.');
    }
}
