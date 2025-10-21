<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

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
        $total_sucursales = \App\Models\Sucursal::count();
        $total_categorias = \App\Models\Categoria::count();
        $total_productos = \App\Models\Producto::count();
        $total_proveedores = \App\Models\Proveedor::count();
        $total_compras = \App\Models\Compra::count();
        $total_lotes_vencidos = \App\Models\Lote::where('fecha_vencimiento', '<', Carbon::now())->count();
        return view('home', compact('total_sucursales', 'total_categorias', 'total_productos', 'total_proveedores', 'total_compras', 'total_lotes_vencidos'));
    }
}
