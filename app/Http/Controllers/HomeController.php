<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use \App\Models\Sucursal;
use \App\Models\Categoria;
use \App\Models\Producto;
use \App\Models\Proveedor;
use \App\Models\Compra;
use \App\Models\Lote;
use \App\Models\ExchangeRates;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $hoy = Carbon::now();
        $limite = Carbon::now()->addDays(7);

        $sucursalId = Auth::user()->persona->sucursal_id ?? 1;

        $total_sucursales = Sucursal::count();
        $total_categorias = Categoria::count();
        $total_productos = Producto::count();
        $total_proveedores = Proveedor::count();
        $total_compras = Compra::count();

        // Lotes vencidos
        $total_lotes_vencidos = Lote::whereDate('fecha_vencimiento', '<=', $hoy)
            ->where('estado', 1)
            ->count();
            
        $total_lotes_por_vencer = Lote::whereBetween(
            'fecha_vencimiento',
            [$hoy, $limite]
        )->count();

        $ultimaTasa = ExchangeRates::latest()->first();
        $productos_stock_minimo = Producto::select(
                'productos.id',
                'productos.nombre',
                'productos.stock_minimo',
                DB::raw('COALESCE(SUM(inventario_sucursal_lotes.cantidad), 0) as stock_actual')
            )
            ->join('lotes', 'lotes.producto_id', '=', 'productos.id')
            ->join('inventario_sucursal_lotes', function($join) use ($sucursalId) {
                $join->on('inventario_sucursal_lotes.lote_id', '=', 'lotes.id')
                     ->where('inventario_sucursal_lotes.sucursal_id', '=', $sucursalId);
            })
            ->where('productos.estado', 1)
            ->groupBy('productos.id', 'productos.nombre', 'productos.stock_minimo')
            ->havingRaw('SUM(inventario_sucursal_lotes.cantidad) <= productos.stock_minimo')
            ->havingRaw('SUM(inventario_sucursal_lotes.cantidad) > 0')
            ->orderBy('stock_actual', 'asc')
            ->get();

        $total_productos_stock_minimo = $productos_stock_minimo->count();

        return view(
            'home',
            [
                'variacion_dolar' => $ultimaTasa?->variacion,
                'tasa_actual' => $ultimaTasa?->tasa,
            ],
            compact(
                'total_sucursales',
                'total_categorias',
                'total_productos',
                'total_proveedores',
                'total_compras',
                'total_lotes_vencidos',
                'total_lotes_por_vencer',
                'productos_stock_minimo',
                'total_productos_stock_minimo'
            )
        );
    }
}