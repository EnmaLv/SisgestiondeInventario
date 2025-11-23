<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Sucursal;
use App\Models\Lote;
use App\Models\DetalleCompra;
use App\Models\InventarioSucursalLote;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use App\Mail\CompraProveedorMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $activo = $request->input('estado');

        $query = Compra::query();

        if ($buscar) {
            $query->where(function($q) use ($buscar) {
                
                $q->where('codigo','like', "%{$buscar}%")
                ->orWhere('nombre','like', "%{$buscar}%");
            });
        }

        if ($activo !== null && $activo !== '') {
            $query->where('estado', (int)$activo);
        }

        $compras = $query->orderBy('id','desc')->paginate(10);

        return view('admin.movimientos.compras.index', compact('compras'));
    }

    public function create()
    {
        $proveedores = \App\Models\Proveedor::all();
        $productos = \App\Models\Producto::all();
        $sucursales = \App\Models\Sucursal::all();
        return view('admin.movimientos.compras.create', compact('proveedores', 'productos', 'sucursales'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'proveedor_id' => 'required|exists:proveedors,id',
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string|max:255',
        ]);

        $compra = new \App\Models\Compra();
        $compra->proveedor_id = $validated['proveedor_id'];
        $compra->fecha = $validated['fecha'];
        $compra->observaciones = $validated['observaciones'] ?? null;
        $compra->total = 0;
        $compra->estado = 'Pendiente';
        $compra->save();

        return redirect()->route('admin.movimientos.compras.edit', $compra->id)->with('success', 'Compra creada exitosamente, Ahora puedes agregar productos.')->with('icono', 'success');
    }

    public function show($id)
    {
        $compra = \App\Models\Compra::findOrFail($id);
        $compra->load('detalleCompras.lote.producto', 'detalleCompras.lote.proveedor', 'proveedor');

        $movimiento_entradas = MovimientoInventario::whereHas('lote', function ($query) use ($compra) {
            $query->whereIn('id', $compra->detalleCompras->pluck('lote_id'));
        })->where('tipo_movimiento', 'Entrada')->first();

        $sucursal_destino = null;
        if ($movimiento_entradas) {
            $sucursal_destino = Sucursal::find($movimiento_entradas->sucursal_id);
        }

        return view('admin.movimientos.compras.show', compact('compra', 'sucursal_destino'));
    }

    public function edit($id)
    {
        $compra = \App\Models\Compra::findOrFail($id);
        $proveedores = \App\Models\Proveedor::all();
        $productos = \App\Models\Producto::all();
        $sucursales = \App\Models\Sucursal::all();
        return view('admin.movimientos.compras.edit', compact('compra', 'proveedores', 'productos', 'sucursales'));
    }

    public function destroy($id)
    {
        $compra = \App\Models\Compra::findOrFail($id);

        DB::beginTransaction();
        try {
            foreach ($compra->detalleCompras as $detalle) {
                $lote = $detalle->lote;

                $lote->delete();
                $detalle->delete();
            }
            $compra->delete();

            DB::commit();
            
            return redirect()->route('admin.movimientos.compras.index')->with('mensaje', 'Compra eliminada exitosamente')->with('icono', 'success');
        } catch (\Exception $e) {
            return redirect()->route('admin.movimientos.compras.index')->with('mensaje', 'Error al eliminar la compra: ' . $e->getMessage())->with('icono', 'error');
        }
    }

    public function enviarCorreo(Compra $compra)
    {
        $compra->load('detalleCompras.producto', 'proveedor');

        $compra->estado = 'Enviado al proveedor';
        $compra->save();

        $proveedorEmail = $compra->proveedor->email;

        Mail::to($proveedorEmail)->send(new CompraProveedorMail($compra));
        return redirect()->route('admin.movimientos.compras.edit', $compra->id)->with('mensaje', 'Correo Enviado Exitosamente al Proveedor')->with('icono', 'success');
    }

    public function finalizarCompra(Request $request, Compra $compra)
    {
        $compra->load('detalleCompras.producto', 'proveedor');

        if ($compra->detalleCompras->isEmpty()) {
            return redirect()->route('admin.movimientos.compras.edit', $compra->id)->with('mensaje', 'No se puede finalizar una compra sin productos.')->with('icono', 'error');
        }

        $request->validate([
            'sucursal_id' => 'required|exists:sucursals,id',
        ]);

        DB::beginTransaction();
        try {
            foreach ($compra->detalleCompras as $detalle) {

                $lote = $detalle->lote;
                $producto = $detalle->producto;

                // Actualizar cantidad del lote general
                $lote->cantidad_actual += $detalle->cantidad;
                $lote->save();

                // Inventario en sucursal por lote (corregido)
                $inventarioLote = InventarioSucursalLote::firstOrCreate(
                    [
                        'lote_id' => $lote->id,
                        'sucursal_id' => $request->sucursal_id,
                    ],
                    [
                        'cantidad' => 0,
                    ]
                );

                $inventarioLote->cantidad += $detalle->cantidad;
                $inventarioLote->save();

                MovimientoInventario::create([
                    'producto_id' => $producto->id,
                    'lote_id' => $lote->id,
                    'sucursal_id' => $request->sucursal_id,
                    'tipo_movimiento' => 'Entrada',
                    'cantidad' => $detalle->cantidad,
                    'fecha' => now(),
                ]);
            }


            $compra->estado = 'Finalizada';
            $compra->save();
            DB::commit();

            return redirect()->route('admin.movimientos.compras.index')->with('mensaje', 'Compra Finalizada Exitosamente')->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.movimientos.compras.index')->with('mensaje', 'Error al finalizar la compra: ' . $e->getMessage())->with('icono', 'error');
        }

        /* $compra->estado = 'Finalizada';
        $compra->save();
        return redirect()->route('admin.movimientos.compras.edit', $compra->id)->with('mensaje', 'Compra Finalizada Exitosamente')->with('icono', 'success'); */
    }

    
}
