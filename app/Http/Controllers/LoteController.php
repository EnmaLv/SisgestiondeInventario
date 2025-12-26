<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lote;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\DetalleCompra;
use App\Models\MovimientoInventario;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LoteController extends Controller
{
    private function procesarLotesVencidos()
    {
        $lotesVencidos = Lote::whereDate('fecha_vencimiento', '<', now()->toDateString())
            ->where('estado', 'ACTIVO')
            ->get();

        foreach ($lotesVencidos as $lote) {
            foreach ($lote->inventarioSucursalLotes as $inv) {

                if ($inv->cantidad_gramos <= 0) continue;

                MovimientoInventario::create([
                    'producto_id'     => $lote->producto_id,
                    'lote_id'         => $lote->id,
                    'sucursal_id'     => $inv->sucursal_id,
                    'tipo_movimiento' => 'MERMA',
                    'cantidad_gramos' => $inv->cantidad_gramos,
                    'fecha'           => now(),
                    'observaciones'   => 'Producto vencido'
                ]);

                $inv->cantidad_gramos = 0;
                $inv->cantidad = 0;
                $inv->save();
            }

            $lote->estado = 'VENCIDO';
            $lote->cantidad_actual = 0;
            $lote->save();
        }
    }
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $estado = $request->input('estado');
        $fecha_desde = $request->input('fecha_desde');
        $fecha_hasta = $request->input('fecha_hasta');

        $query = Lote::with('producto', 'proveedor');

        if ($request->filled('estado')) {
            $query->where('estado', (int) $estado);
        } else {
            $query->where('estado', 1);
        }

        if ($buscar) {
            $query->where('codigo_lote', 'like', "%{$buscar}%");
        }

        if ($fecha_desde && $fecha_hasta) {
            $query->whereBetween('fecha_vencimiento', [$fecha_desde, $fecha_hasta]);
        } elseif ($fecha_desde) {
            $query->where('fecha_entrada', '>=', $fecha_desde);
        } elseif ($fecha_hasta) {
            $query->where('fecha_vencimiento', '<=', $fecha_hasta);
        }

        $lotes = $query->orderBy('id', 'desc')->paginate(10);
        $lotes->each(function ($lote) {
            $fecha = Carbon::parse($lote->fecha_vencimiento);
            $lote->is_expired = $fecha->isPast();
            $lote->days_to_expire = now()->diffInDays($fecha, false);
        });

        return view('admin.movimientos.lotes.index', compact('lotes'));
    }

}
