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
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $activo = $request->input('estado');

        $query = Lote::query();

        if ($buscar) {
            $query->where(function($q) use ($buscar) {
                
                $q->where('codigo','like', "%{$buscar}%")
                ->orWhere('nombre','like', "%{$buscar}%");
            });
        }

        if ($activo !== null && $activo !== '') {
            $query->where('estado', (int)$activo);
        }

        $lotes = $query->orderBy('id','desc')->paginate(10);
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
