<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lote;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\DetalleCompra;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LoteController extends Controller
{
    public function index()
    {
        /*  $lotes = Lote::all();
        return response()->json([
            $lotes,
        ]); */
        $lotes = Lote::all();
        $productos = Producto::all();
        $proveedores = Proveedor::all();
        $detalleCompras = DetalleCompra::all();

        $lotes->each(function ($lote) {
            /* $lote->cantidad_actual = $lote->detalleCompras->sum('cantidad'); */
            $lote->is_expired = Carbon::parse($lote->fecha_vencimiento)->isPast();
        });
        return view('admin.movimientos.lotes.index', compact('lotes', 'productos', 'proveedores', 'detalleCompras'));
    }
}
