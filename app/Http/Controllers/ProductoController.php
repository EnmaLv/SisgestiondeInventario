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

        $query = Producto::query();

        if ($buscar) {
            $query->where(function($q) use ($buscar) {
                
                $q->where('codigo','like', "%{$buscar}%")
                ->orWhere('nombre','like', "%{$buscar}%");
            });
        }

        if ($activo !== null && $activo !== '') {
            $query->where('estado', (int)$activo);
        }

        $productos = $query->orderBy('id','desc')->paginate(10);

        return view('admin.maestros.productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        $unidades = Unidad::all();
        return view('admin.maestros.productos.create', compact('categorias', 'unidades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductoRequest $request)
    {
        try {
            $validated = $request->validated();

            // Obtener categoría
            $categoria = Categoria::findOrFail($validated['categoria_id']);

            // Crear código automático
            $codigo = strtoupper(
                substr($categoria->nombre, 0, 3) . '-' .
                substr($validated['nombre'], 0, 3) . '-' .
                rand(100, 999)
            );

            // Reemplazar código
            $validated['codigo'] = $codigo;

            $producto = new Producto();
            $producto->categoria_id = $validated['categoria_id'];
            $producto->codigo = $validated['codigo'];
            $producto->nombre = $validated['nombre'];
            $producto->descripcion = $validated['descripcion'];
            
            if ($request->hasFile('imagen')) {
                $producto->imagen = $request->file('imagen')->store('imagenes/productos', 'public');
            } else {
                $producto->imagen = 'imagenes/productos/default.png';
            }
            
            $producto->precio_compra = $validated['precio_compra'];
            $producto->stock_minimo = $validated['stock_minimo'];
            $producto->stock_maximo = $validated['stock_maximo'];
            $producto->unidad_id = $validated['unidad_id'];
            $producto->estado = $validated['estado'] ?? true;

            $producto->save();

            return redirect()->route('admin.maestros.productos.index')
                ->with('success', 'Producto creado exitosamente.')
                ->with('icon', 'success');
                
        } catch (\Exception $e) {
            \Log::error('Error al crear producto: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear producto: ' . $e->getMessage());
        }
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
        $categorias = Categoria::all();
        $unidades = Unidad::all();
        return view('admin.maestros.productos.edit', compact('producto', 'categorias', 'unidades'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductoRequest $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $validated = $request->validated();

        $producto->codigo = $validated['codigo'];
        $producto->nombre = $validated['nombre'];
        $producto->descripcion = $validated['descripcion'];
        if ($request->hasFile('imagen')) {
            $producto->imagen = $request->file('imagen')->store('imagenes/productos', 'public');
        } else {
            // Mantener la imagen actual si no se sube una nueva
            $producto->imagen = $producto->imagen;
        }
        $producto->precio_compra = $validated['precio_compra'];
        $producto->stock_minimo = $validated['stock_minimo'];
        $producto->stock_maximo = $validated['stock_maximo'];
        $producto->unidad_id = $validated['unidad_id'];
        $producto->categoria_id = $validated['categoria_id'];
        $producto->estado = true;
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
