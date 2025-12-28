<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use \App\Models\Sucursal;
use \App\Models\Categoria;
use \App\Models\Producto;
use \App\Models\Proveedor;
use \App\Models\Compra;
use \App\Models\Lote;
use \App\Models\ExchangeRates;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $hoy = Carbon::now();
        $limite = Carbon::now()->addDays(7);

        $total_sucursales = Sucursal::count();
        $total_categorias = Categoria::count();
        $total_productos = Producto::count();
        $total_proveedores = Proveedor::count();
        $total_compras = Compra::count();

        // Lotes vencidos
        $total_lotes_vencidos = Lote::whereDate('fecha_vencimiento', '<=', $hoy)
            ->where('estado', 1)
            ->count();


        // Lotes por vencer (≤ 7 días y NO vencidos)
        $total_lotes_por_vencer = Lote::whereBetween(
            'fecha_vencimiento',
            [$hoy, $limite]
        )->count();

        $ultimaTasa = ExchangeRates::latest()->first();

        return view('home', ['variacion_dolar' => $ultimaTasa?->variacion,'tasa_actual' => $ultimaTasa?->tasa,], 
        compact(
            'total_sucursales',
            'total_categorias',
            'total_productos',
            'total_proveedores',
            'total_compras',
            'total_lotes_vencidos',
            'total_lotes_por_vencer'
        ));
    }


}
