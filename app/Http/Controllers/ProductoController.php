<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Unidad;
use Illuminate\Http\Request;

use App\Http\Requests\ProductoRequest;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $activo = $request->input('estado');

        // Usa el método del modelo (query builder encapsulado)
        $productos = \App\Models\Producto::listarProductos($buscar, $activo, 10);

        return view('admin.maestros.productos.index', compact('productos'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $datos = \App\Models\Producto::getDatosFormulario();
        return view('admin.maestros.productos.create', $datos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductoRequest $request)
    {
        $validated = $request->validated();

        // Manejo de imagen (el controlador gestiona el filesystem)
        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('imagenes/productos', 'public');
            $validated['imagen'] = $path;
        } else {
            $validated['imagen'] = 'imagenes/productos/default.png';
        }

        // Delegar creación al modelo (query builder)
        $productoId = Producto::crearProducto($validated);

        return redirect()->route('admin.maestros.productos.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    public function show($id)
    {
        $producto = Producto::obtenerProducto($id);
        return view('admin.maestros.productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $datos = Producto::getDatosFormulario();

        return view('admin.maestros.productos.edit', array_merge($datos, [
            'producto' => $producto
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductoRequest $request, $id)
    {
        $validated = $request->validated();

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('imagenes/productos', 'public');
            $validated['imagen'] = $path;
        }

        Producto::actualizarProducto($id, $validated);

        return redirect()->route('admin.maestros.productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy($id)
    {
        Producto::eliminarProducto($id);
        return redirect()->route('admin.maestros.productos.index')->with('success', 'Producto eliminado exitosamente.');
    }
}
