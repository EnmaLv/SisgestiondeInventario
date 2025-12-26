<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Unidad;
use Illuminate\Http\Request;
use App\Models\ExchangeRates;
use App\Models\PrecioProducto;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;


use App\Http\Requests\ProductoRequest;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $activo = $request->input('activo', 1); // Por defecto muestra solo activos
        $productos = Producto::listarProductos($request->buscar, $activo);
        return view('admin.maestros.productos.index', compact('productos'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $datos = Producto::getDatosFormulario();
        return view('admin.maestros.productos.create', $datos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductoRequest $request)
{
    DB::beginTransaction();

    try {
        $validated = $request->validated();

        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')
                ->store('imagenes/productos', 'public');
        } else {
            $validated['imagen'] = 'imagenes/productos/default.png';
        }

        $productoId = Producto::crearProducto($validated);

        $this->procesarTasaYPrecios($productoId);

        DB::commit();

        return redirect()
            ->route('admin.maestros.productos.index')
            ->with('success', 'Producto creado y precio actualizado correctamente.');

    } catch (\Exception $e) {

        DB::rollBack();
        \Log::error('Error al crear producto', ['error' => $e->getMessage()]);

        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Error al crear el producto.');
    }
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
        return redirect()->route('admin.maestros.productos.index')->with('success', 'Producto inactivado exitosamente.');
    }

    public function activar($id)
    {
        Producto::activarProducto($id);
        return redirect()->route('admin.maestros.productos.index')->with('success', 'Producto activado exitosamente.');
    }

    /**
     * Actualiza la tasa del dólar y recalcula los precios de todos los productos
     */
    public function actualizarTasaDolar()
    {
        DB::beginTransaction();

        try {
            $response = Http::get('https://ve.dolarapi.com/v1/dolares/oficial');

            if (!$response->ok()) {
                return redirect()->back()->with('error', 'No se pudo obtener la tasa del dólar.');
            }

            $data = $response->json();

            $tasa = ExchangeRates::firstOrCreate(
                ['nombre' => 'Oficial'],
                [
                    'fuente'   => 'oficial',
                    'promedio' => 0
                ]
            );

            $tasa->forceFill([
                'fuente'   => $data['fuente'],
                'promedio' => $data['promedio'],
            ])->save();

            $productos = Producto::with('precioProducto')->get();

            foreach ($productos as $producto) {

                if (!$producto->precioProducto) {
                    continue;
                }

                $precioUSD = $producto->precioProducto->precio_usd
                    ?? $producto->precioProducto->costo_usd;

                if (!$precioUSD || $precioUSD <= 0) {
                    continue;
                }

                $margen = $producto->precioProducto->margen ?? 0;

                $precioBs = round(
                    $precioUSD * (1 + $margen / 100) * $tasa->promedio,
                    2
                );

                DB::table('productos')
                    ->where('id', $producto->id)
                    ->update([
                        'precio_compra' => $precioBs,
                        'updated_at'    => now()
                    ]);
            }


            DB::commit();

            return redirect()->back()->with(
                'success',
                'Tasa actualizada correctamente y precios recalculados.'
            );

        } catch (\Exception $e) {
            \Log::error('Error al actualizar la tasa', [
                'error' => $e->getMessage()
            ]);
            DB::rollBack();

            return redirect()->back()->with(
                'error',
                'Ocurrió un error al actualizar la tasa.'
            );
        }
    }

    private function procesarTasaYPrecios(?int $productoId = null)
    {
        $response = Http::get('https://ve.dolarapi.com/v1/dolares/oficial');

        if (!$response->ok()) {
            throw new \Exception('No se pudo obtener la tasa del dólar');
        }

        $data = $response->json();

        $tasa = ExchangeRates::firstOrCreate(
            ['nombre' => 'Oficial'],
            ['fuente' => 'oficial', 'promedio' => 0]
        );

        $tasa->update([
            'fuente'   => $data['fuente'],
            'promedio' => $data['promedio'],
        ]);

        $query = Producto::with('precioProducto');

        if ($productoId) {
            $query->where('id', $productoId); 
        }

        $productos = $query->get();

        foreach ($productos as $producto) {

            if (!$producto->precioProducto) {
                continue;
            }

            $precioUSD = $producto->precioProducto->costo_usd
                ?? $producto->precioProducto->precio_usd
                ?? 0;

            if ($precioUSD <= 0) {
                continue;
            }

            $margen = $producto->precioProducto->margen ?? 0;

            $precioBs = round(
                $precioUSD * (1 + $margen / 100) * $tasa->promedio,
                2
            );

            DB::table('productos')
                ->where('id', $producto->id)
                ->update([
                    'precio_compra' => $precioBs,
                    'updated_at'    => now()
                ]);
        }

    }


}
