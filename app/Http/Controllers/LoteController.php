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
        $fecha_desde = $request->input('fecha_desde');
        $fecha_hasta = $request->input('fecha_hasta');

        $query = Lote::with('producto', 'proveedor');

        if ($buscar) {
            $query->where(function ($q) use ($buscar) {
                $q->where('codigo', 'like', "%{$buscar}%")
                ->orWhere('nombre', 'like', "%{$buscar}%");
            });
        }

        if ($activo !== null && $activo !== '') {
            $query->where('estado', (int) $activo);
        }

        if ($fecha_desde && $fecha_hasta) {
            $query->whereBetween('fecha_vencimiento', [$fecha_desde, $fecha_hasta]);
        } elseif ($fecha_desde) {
            $query->where('fecha_entrada', '>=', $fecha_desde);
        } elseif ($fecha_hasta) {
            $query->where('fecha_vencimiento', '<=', $fecha_hasta);
        }


        $lotes = $query->orderBy('id', 'desc')->paginate(10);

        // Expiración
        $lotes->each(function ($lote) {
            $fecha_vencimiento = Carbon::parse($lote->fecha_vencimiento);
            $lote->is_expired = $fecha_vencimiento->isPast();
            $hoy = Carbon::now();
            $lote->days_to_expire = $hoy->diffInDays($fecha_vencimiento, false);
        });

        $productos = Producto::all();
        $proveedores = Proveedor::all();
        $detalleCompras = DetalleCompra::all();

        return view('admin.movimientos.lotes.index',
            compact('lotes', 'productos', 'proveedores', 'detalleCompras')
        );
    }

}
