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
        $total_sucursales = Sucursal::count();
        $total_categorias = Categoria::count();
        $total_productos = Producto::count();
        $total_proveedores = Proveedor::count();
        $total_compras = Compra::count();
        $total_lotes_vencidos = Lote::where('fecha_vencimiento', '<', Carbon::now())->count();
        return view('home', compact('total_sucursales', 'total_categorias', 'total_productos', 'total_proveedores', 'total_compras', 'total_lotes_vencidos'));
    }
}
