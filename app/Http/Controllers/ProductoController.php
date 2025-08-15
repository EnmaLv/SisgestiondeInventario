<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::all();
        return view('admin.maestros.productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = \App\Models\Categoria::all();
        return view('admin.maestros.productos.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'codigo' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'precio_compra' => 'required|numeric',
            'precio_venta' => 'required|numeric',
            'stock_minimo' => 'required|integer',
            'stock_maximo' => 'required|integer',
            'unidad_medida' => 'required',
            'estado' => 'required|boolean',
        ]);

        $producto = new Producto();
        $producto->categoria_id = $validated['categoria_id'];
        $producto->codigo = $validated['codigo'];
        $producto->nombre = $validated['nombre'];
        $producto->descripcion = $validated['descripcion'];
        if ($request->hasFile('imagen')) {
            $producto->imagen = $request->file('imagen')->store('imagenes/productos', 'public');
        }
        $producto->precio_compra = $validated['precio_compra'];
        $producto->precio_venta = $validated['precio_venta'];
        $producto->stock_minimo = $validated['stock_minimo'];
        $producto->stock_maximo = $validated['stock_maximo'];
        $producto->unidad_medida = $validated['unidad_medida'];
        $producto->estado = $validated['estado'];
        $producto->save();

        return redirect()->route('admin.maestros.productos.index')->with('success', 'Producto creado exitosamente.')->with('icon', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return view('admin.maestros.productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        
        $producto = Producto::findOrFail($id);
        $categorias = \App\Models\Categoria::all();
        return view('admin.maestros.productos.edit', compact('producto', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $validated = $request->validate([
            'codigo' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'precio_compra' => 'required|numeric',
            'precio_venta' => 'required|numeric',
            'stock_minimo' => 'required|integer',
            'stock_maximo' => 'required|integer',
            'unidad_medida' => 'required|string|max:100',
            'estado' => 'required|boolean',
        ]);

        $producto->codigo = $validated['codigo'];
        $producto->nombre = $validated['nombre'];
        $producto->descripcion = $validated['descripcion'];
        if ($request->hasFile('imagen')) {
            $producto->imagen = $request->file('imagen')->store('imagenes/productos', 'public');
        }
        $producto->precio_compra = $validated['precio_compra'];
        $producto->precio_venta = $validated['precio_venta'];
        $producto->stock_minimo = $validated['stock_minimo'];
        $producto->stock_maximo = $validated['stock_maximo'];
        $producto->unidad_medida = $validated['unidad_medida'];
        $producto->estado = $validated['estado'];
        $producto->save();

        return redirect()->route('admin.maestros.productos.index')->with('success', 'Producto actualizado exitosamente.')->with('icon', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('admin.maestros.productos.index')->with('success', 'Producto eliminado exitosamente.')->with('icon', 'success');
    }
}
