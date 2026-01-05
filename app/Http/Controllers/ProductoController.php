<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use App\Models\ExchangeRates;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;


use App\Http\Requests\ProductoRequest;

class ProductoController extends Controller
{

    public function index(Request $request)
    {
        $activo = $request->input('activo', 1);
        $productos = Producto::listarProductos($request->buscar, $activo);
        return view('admin.maestros.productos.index', compact('productos'));
    }

    public function create()
    {
        $datos = Producto::getDatosFormulario();
        return view('admin.maestros.productos.create', $datos);
    }

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

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $datos = Producto::getDatosFormulario();

        return view('admin.maestros.productos.edit', array_merge($datos, [
            'producto' => $producto
        ]));
    }

    public function update(ProductoRequest $request, $id)
    {
        $validated = $request->validated();

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('imagenes/productos', 'public');
            $validated['imagen'] = $path;
        }

        Producto::actualizarProducto($id, $validated);
        $this->procesarTasaYPrecios($id);

        return redirect()->route('admin.maestros.productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy($id)
    {
        if (Producto::tieneInventarioEnAcarigua($id)) {

            $cantidad = Producto::cantidadEnAcarigua($id);

            return redirect()
                ->route('admin.maestros.productos.index')
                ->with(
                    'error',
                    "No se puede eliminar el producto porque aún tiene " . round($cantidad) . " unidad(es) en el inventario."
                )
                ->with('icono', 'error');
        }

        Producto::eliminarProducto($id);

        return redirect()
            ->route('admin.maestros.productos.index')
            ->with('success', 'Producto inactivado exitosamente.');
    }


    public function activar($id)
    {
        Producto::activarProducto($id);
        return redirect()->route('admin.maestros.productos.index')->with('success', 'Producto activado exitosamente.');
    }

    public function actualizarTasaDolar()
    {
        DB::beginTransaction();

        try {
            $response = Http::get('https://ve.dolarapi.com/v1/dolares/oficial');

            if (!$response->ok()) {
                return redirect()->back()->with('error', 'No se pudo obtener la tasa del dólar.');
            }

            $data = $response->json();

            $fechaVigencia = isset($data['fecha'])
                ? \Carbon\Carbon::parse($data['fecha'])->toDateString()
                : now()->toDateString();

            $tasa = ExchangeRates::updateOrCreate(
                [
                    'nombre'         => 'Oficial',
                    'fecha_vigencia' => $fechaVigencia
                ],
                [
                    'fuente'   => $data['fuente'],
                    'promedio' => $data['promedio'],
                ]
            );

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

            session()->forget('tasa_pendiente');

            return redirect()->route('home')->with(
                'success',
                'Tasa registrada y precios recalculados correctamente.'
            );

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error al actualizar la tasa', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with(
                'error',
                'Ocurrió un error al actualizar la tasa.'
            );
        }
    }


    private function procesarTasaYPrecios(?int $productoId = null)
    {
        $tasa = ExchangeRates::orderByDesc('fecha_vigencia')->first();

        if (!$tasa) {
            throw new \Exception('No existe una tasa registrada');
        }

        $query = Producto::with('precioProducto');

        if ($productoId) {
            $query->where('id', $productoId);
        }

        $productos = $query->get();

        foreach ($productos as $producto) {

            if (!$producto->precioProducto) {
                continue;
            }

            $precioUSD =
                $producto->precioProducto->costo_usd ??
                $producto->precioProducto->precio_usd ??
                0;

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
