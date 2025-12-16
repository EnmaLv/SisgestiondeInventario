<?php

namespace App\Livewire\Admin\Movimientos\Compras;

use Livewire\Component;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Lote;

class FechasCompra extends Component
{
    public $compra;
    public $fechas = [];

    protected $rules = [
        'fechas' => 'required|date|after:today',
    ];

    public function mount(Compra $compra)
    {
        $this->compra = $compra;

        foreach ($compra->detalleCompras as $detalle) {
            $this->fechas[$detalle->id] = [
                'fecha_vencimiento' => optional($detalle->lote)->fecha_vencimiento,
                'cantidad_recibida' => $detalle->cantidad,
                'lote_id' => $detalle->lote_id,
            ];
        }
    }

    public function guardar()
    {
        foreach ($this->fechas as $detalleId => $datos) {

            // 1️⃣ actualizar la cantidad en detalle de compra
            DetalleCompra::where('id', $detalleId)
                ->update([
                    'cantidad' => $datos['cantidad_recibida'],
                ]);

            // 2️⃣ actualizar la fecha de vencimiento en el lote
            Lote::where('id', $datos['lote_id'])
                ->update([
                    'fecha_vencimiento' => $datos['fecha_vencimiento'],
                ]);
        }
        $this->dispatch(
            'mostrar-alerta',
            icono: 'success',
            mensaje: 'Fecha guardada correctamente'
        );
    }

    public function render()
    {
        return view('livewire.admin.movimientos.compras.fechas-compra');
    }
}
