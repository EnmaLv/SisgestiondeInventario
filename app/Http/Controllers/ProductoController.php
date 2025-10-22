<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

use App\Http\Requests\ProductoRequest;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::paginate(10) ;
        if(request()->ajax())
        {
            return view('admin.maestros.productos.indexContent', compact('productos'));
        }
        return view('admin.maestros.productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        if(request()->ajax())
        {
            return view('admin.maestros.productos.createContent', compact('categorias'));
        }
        return view('admin.maestros.productos.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductoRequest $request)
    {
        //validamos los datos de la solicitud
        $validated = $request->validated();

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
        if(request()->ajax())
        {
            return view('admin.maestros.productos.showContent', compact('producto'));
        }
        return redirect()->route('admin.maestros.productos.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::all();
        if($request->ajax())
        {
            return view('admin.maestros.productos.editContent', compact('producto', 'categorias'));
        }
        return redirect()->route('admin.maestros.productos.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductoRequest $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $validated = $request->validated();
        try{

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

            return response()->json([
                'message' => 'Producto actualizado exitosamente.',
                'icon' => 'success'
            ]);

        }catch(Exception $e){
            return response()->json([
                'message' => 'Error al actualizar el producto.',
                'icon' => 'error'
            ]);
        }

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
