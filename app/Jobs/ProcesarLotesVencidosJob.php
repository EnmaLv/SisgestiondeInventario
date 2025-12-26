<?php

namespace App\Jobs;

use App\Models\Lote;
use App\Models\MovimientoInventario;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcesarLotesVencidosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        DB::transaction(function () {

            $lotesVencidos = Lote::with([
                    'producto',                 // 👈 IMPORTANTE
                    'inventarioSucursalLotes'
                ])
                ->whereDate('fecha_vencimiento', '<=', now())
                ->where('estado', 1)
                ->get();

            foreach ($lotesVencidos as $lote) {

                // 🔹 La unidad viene del producto
                $unidadId = $lote->producto->unidad_id;

                foreach ($lote->inventarioSucursalLotes as $inv) {

                    if ($inv->cantidad_gramos <= 0) {
                        continue;
                    }

                    MovimientoInventario::create([
                        'producto_id'      => $lote->producto_id,
                        'lote_id'          => $lote->id,
                        'sucursal_id'      => $inv->sucursal_id,
                        'tipo_movimiento'  => 'MERMA',
                        'unidad_id'        => $unidadId, // ✅ CORRECTO
                        'cantidad'         => 0,
                        'cantidad_gramos'  => $inv->cantidad_gramos,
                        'fecha'            => now(),
                        'observaciones'    => 'Producto vencido – retiro automático'
                    ]);

                    // Vaciar inventario del lote
                    $inv->update([
                        'cantidad' => 0,
                        'cantidad_gramos' => 0
                    ]);
                }

                // Marcar lote como procesado
                $lote->update([
                    'cantidad_inicial' => 0,
                    'cantidad_actual' => 0,
                    'estado' => 0
                ]);
            }
        });
    }
}
