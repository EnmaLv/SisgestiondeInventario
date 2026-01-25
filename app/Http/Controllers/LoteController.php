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
        DB::transaction(function () {
            $lotesVencidos = Lote::with(['producto', 'inventarioSucursalLotes'])
                ->whereDate('fecha_vencimiento', '<=', now())
                ->where('estado', 1)
                ->get();

            foreach ($lotesVencidos as $lote) {
                $unidadId = $lote->producto->unidad_id;

                foreach ($lote->inventarioSucursalLotes as $inv) {
                    if ($inv->cantidad_gramos <= 0) {
                        continue;
                    }

                    MovimientoInventario::create([
                        'producto_id'     => $lote->producto_id,
                        'lote_id'         => $lote->id,
                        'sucursal_id'     => $inv->sucursal_id,
                        'tipo_movimiento' => 'MERMA',
                        'unidad_id'       => $unidadId,
                        'cantidad'        => 0,
                        'cantidad_gramos' => $inv->cantidad_gramos,
                        'fecha'           => now(),
                        'observaciones'   => 'Producto vencido – merma manual'
                    ]);

                    $inv->update([
                        'cantidad' => 0,
                        'cantidad_gramos' => 0
                    ]);
                }

                $lote->update([
                    'cantidad_inicial' => 0,
                    'cantidad_actual'  => 0,
                    'estado'           => 0
                ]);
            }
        });
    }

    public function mermarVencidos()
    {
        $this->procesarLotesVencidos();
        return redirect()->route('admin.movimientos.lotes.index')->with('success', 'Productos vencidos mermados Exitosamente.');
    }

    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $estado = $request->input('estado');
        $fecha_desde = $request->input('fecha_desde');
        $fecha_hasta = $request->input('fecha_hasta');
        $filtro = $request->input('filtro');


        $sucursalId = 1;

        $query = Lote::with(['producto', 'proveedor'])
            ->addSelect([
                'lotes.*',
                'cantidad_sucursal' => DB::table('inventario_sucursal_lotes')
                    ->selectRaw('COALESCE(SUM(cantidad), 0)')
                    ->whereColumn('inventario_sucursal_lotes.lote_id', 'lotes.id')
                    ->where('inventario_sucursal_lotes.sucursal_id', $sucursalId),
                'cantidad_gramos_sucursal' => DB::table('inventario_sucursal_lotes')
                    ->selectRaw('COALESCE(SUM(cantidad_gramos), 0)')
                    ->whereColumn('inventario_sucursal_lotes.lote_id', 'lotes.id')
                    ->where('inventario_sucursal_lotes.sucursal_id', $sucursalId)
            ]);

        if ($filtro === 'vencido') {
            $query->whereDate('fecha_vencimiento', '<', now()->addDays(1));
        }

        if ($filtro === 'por_vencer') {
            $query->whereDate('fecha_vencimiento', '>', now());
        }

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

        $hayLotesVencidosSinMerma = Lote::whereDate('fecha_vencimiento', '<=', now())
            ->where('estado', 1)
            ->exists();

        $sucursalNombre = DB::table('sucursals')->where('id', $sucursalId)->value('nombre');

        return view('admin.movimientos.lotes.index', compact('lotes', 'hayLotesVencidosSinMerma', 'sucursalNombre'));
    }
}
