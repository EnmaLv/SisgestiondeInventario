<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $activo = $request->input('estado');

        $query = Proveedor::query();

        if ($buscar) {
            $query->where(function($q) use ($buscar) {
                
                $q->where('empresa','like', "%{$buscar}%")
                ->orWhere('nombre','like', "%{$buscar}%");
            });
        }

        if ($activo !== null && $activo !== '') {
            $query->where('estado', (int)$activo);
        }

        $proveedores = $query->orderBy('id','desc')->paginate(10);
        
        return view('admin.maestros.proveedores.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.maestros.proveedores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'empresa' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $proveedor = new Proveedor();
        $proveedor->empresa = $validated['empresa'];
        $proveedor->direccion = $validated['direccion'];
        $proveedor->nombre = $validated['nombre'];
        $proveedor->telefono = $validated['telefono'];
        $proveedor->email = $validated['email'];
        $proveedor->estado = true;
        $proveedor->save();

        return redirect()->route('admin.maestros.proveedores.index')->with('success', 'Proveedor creado exitosamente.')->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return view('admin.maestros.proveedores.show', compact('proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return view('admin.maestros.proveedores.edit', compact('proveedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $validated = $request->validate([
            'empresa' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $proveedor->empresa = $validated['empresa'];
        $proveedor->direccion = $validated['direccion'];
        $proveedor->nombre = $validated['nombre'];
        $proveedor->telefono = $validated['telefono'];
        $proveedor->email = $validated['email'];
        $proveedor->save();

        return redirect()->route('admin.maestros.proveedores.index')->with('success', 'Proveedor actualizado exitosamente.')->with('icono', 'success');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->delete();

        return redirect()->route('admin.maestros.proveedores.index')->with('success', 'Proveedor eliminado exitosamente.')->with('icono', 'success');
    }
}
