<?php

namespace App\Livewire\Admin\Movimientos\Compras;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Lote;
use App\Models\Producto;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ItemsCompra extends Component
{
    public Compra $compra;

    public int $productoId;

    public int $cantidad = 1;

    public float $precioUnitario;

    public float $precioCompra;

    public $codigoLote;

    public $fechaVencimiento;

    public $productos;

    public $totalCompra;

    public function mount(Compra $compra)
    {
        $this->compra = $compra;
        $this->productos = Producto::all();
        $this->cargarDatos();
    }

    public function cargarDatos()
    {
        $this->compra->load('detalleCompras.producto', 'detalleCompras.lote');
        $this->totalCompra = $this->compra->detalleCompras->sum('subtotal');

        $this->reset(['productoId', 'codigoLote', 'cantidad', 'precioUnitario', 'precioCompra', 'fechaVencimiento', 'totalCompra']);
        $this->cantidad = 1;
    }

    protected $rules = [
        'productoId' => 'required|exists:productos,id',
        'codigoLote' => 'required|string|max:50',
        'cantidad' => 'required|integer|min:1',
        'precioCompra' => 'required|numeric|min:0',
        'fechaVencimiento' => 'required|date|after:today',
    ];

    public function updatedproductoId($value)
    {
        $producto = Producto::find($value);
        if ($producto) {
            $this->precioCompra = $producto->precio_compra;
        } else {
            $this->precioCompra = 0;
        }
    }

    public function agregarItems()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $producto = Producto::findOrFail($this->productoId);
            $unidadId = $producto->unidad_id;

            $lote = Lote::create([
                'producto_id' => $this->productoId,
                'proveedor_id' => $this->compra->proveedor_id,
                'codigo_lote' => $this->codigoLote,
                'fecha_entrada' => now()->toDateString(),
                'fecha_vencimiento' => $this->fechaVencimiento,
                'cantidad_inicial' => 0,
                'cantidad_actual' => 0,
                'precio_compra' => $this->precioCompra,
                'estado' => true,
            ]);

            $this->compra->detalleCompras()->create([
                'producto_id' => $producto->id,
                'lote_id' => $lote->id,
                'cantidad' => $this->cantidad,
                'precio_unitario' => $this->precioCompra,
                'subtotal' => $this->cantidad * $this->precioCompra,
                'unidad_id' => $unidadId, // ✔️ NECESARIO
            ]);

            $this->compra->total = $this->compra->detalleCompras->sum('subtotal');
            $this->compra->save();

            DB::commit();
            $this->cargarDatos();
            $this->aggItems();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch(
                'mostrar-alerta',
                icono: 'error',
                mensaje: 'Error: ' . $e->getMessage()
            );
        }
    }


    public function render()
    {
        return view('livewire.admin.movimientos.compras.items-compra');
    }

    public function aggItems()
    {
        $this->dispatch(
            'mostrar-alerta',
            icono: 'success',
            mensaje: 'Producto agregado correctamente'
        );
        $this->cantidad = $this->cantidad;
    }

    public function eliminarItem($detalleId)
    {
        DB::beginTransaction();
        try {


            $detalle = DetalleCompra::find($detalleId);

            $lote_id = $detalle->lote_id;
            $lote = Lote::find($lote_id);
            $lote->delete();
            $detalle->delete();



            $this->compra->total = $this->compra->detalleCompras->sum('subtotal');
            $this->compra->save();

            DB::commit();
            $this->cargarDatos();
            $this->dispatch(
                'mostrar-alerta',
                icono: 'success',
                mensaje: 'Producto eliminado correctamente'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch(
                'mostrar-alerta',
                icono: 'error',
                mensaje: 'Error: ' . $e->getMessage()
            );
        }
    }
}
