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
        $proveedores = Proveedor::listarProveedores(
            $request->input('buscar'),
            $request->input('estado')
        );
        
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
            'empresa'   => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'nombre'    => 'required|string|max:255',
            'telefono'  => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:proveedors,email',
        ]);

        Proveedor::crearProveedor($validated);

        return redirect()
            ->route('admin.maestros.proveedores.index')
            ->with('success', 'Proveedor creado exitosamente.')
            ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $proveedor = Proveedor::obtenerProveedorConCompras($id);
        
        if (!$proveedor) {
            return redirect()
                ->route('admin.maestros.proveedores.index')
                ->with('error', 'Proveedor no encontrado.')
                ->with('icono', 'error');
        }

        return view('admin.maestros.proveedores.show', compact('proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $proveedor = Proveedor::obtenerProveedor($id);
        
        if (!$proveedor) {
            return redirect()
                ->route('admin.maestros.proveedores.index')
                ->with('error', 'Proveedor no encontrado.')
                ->with('icono', 'error');
        }

        return view('admin.maestros.proveedores.edit', compact('proveedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'empresa'   => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'nombre'    => 'required|string|max:255',
            'telefono'  => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:proveedors,email,' . $id,
        ]);

        Proveedor::actualizarProveedor($id, $validated);

        return redirect()
            ->route('admin.maestros.proveedores.index')
            ->with('success', 'Proveedor actualizado exitosamente.')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Verificar si tiene compras antes de eliminar
        if (Proveedor::tieneCompras($id)) {
            return redirect()
                ->route('admin.maestros.proveedores.index')
                ->with('error', 'No se puede eliminar el proveedor porque tiene compras asociadas.')
                ->with('icono', 'error');
        }

        Proveedor::eliminarProveedor($id);

        return redirect()
            ->route('admin.maestros.proveedores.index')
            ->with('success', 'Proveedor eliminado exitosamente.')
            ->with('icono', 'success');
    }
}