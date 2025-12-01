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

    // ----------------------
    // Ajusta las reglas: codigoLote puede ser nullable (se genera si no viene)
    // ----------------------
    protected $rules = [
        'productoId' => 'required|exists:productos,id',
        'codigoLote' => 'nullable|string|max:50',
        'cantidad' => 'required|integer|min:1',
        'precioCompra' => 'required|numeric|min:0',
        'fechaVencimiento' => 'required|date|after:today',
    ];

    // ----------------------
    // Cuando cambie producto -> cargar precio y generar código de lote
    // ----------------------
    public function updatedproductoId($value)
    {
        $producto = Producto::with('categoria')->find($value);
        if ($producto) {
            $this->precioCompra = $producto->precio_compra;
            // Generar un código de lote sugerido automáticamente
            $this->codigoLote = $this->generateCodigoLote($producto);
        } else {
            $this->precioCompra = 0;
            $this->codigoLote = null;
        }
    }

    /**
     * Genera un código de lote legible:
     * formato: PRD-YYYYMMDD-XXX
     * PRD = 3 letras del nombre del producto
     * XXX = número random 3 dígitos (si ya existe, se regenera)
     */
    protected function generateCodigoLote(Producto $producto): string
    {
        // 3 letras del nombre del producto, solo alfanumérico
        $prod = $producto->nombre ?? 'PROD';
        $prodPart = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $prod), 0, 3));

        // Año actual
        $year = now()->format('Y');

        // Día juliano (001 - 365/366)
        $julianDay = now()->format('z') + 1; // z = 0–365, por eso +1
        $julian = str_pad($julianDay, 3, '0', STR_PAD_LEFT);

        // Construir código base
        $codigo = "{$julian}-{$year}-{$prodPart}";

        // Garantizar unicidad
        $contador = 1;
        $codigoFinal = $codigo;

        while (Lote::where('codigo_lote', $codigoFinal)->exists()) {
            $codigoFinal = "{$codigo}-{$contador}";
            $contador++;
        }

        return $codigoFinal;
    }


    // ----------------------
    // Nuevo agregarItems (reemplaza el original)
    // ----------------------
    public function agregarItems()
    {
        // Si no hay producto seleccionado, la validación lo atrapará
        // pero generamos lote por si el usuario lo borró manualmente
        $producto = Producto::find($this->productoId);
        if (!$producto) {
            $this->dispatch('mostrar-alerta', icono: 'error', mensaje: 'Seleccione un producto válido.');
            return;
        }

        // Asegurarse que siempre haya un codigo de lote (auto)
        if (empty($this->codigoLote)) {
            $this->codigoLote = $this->generateCodigoLote($producto);
        }

        // validamos (codigoLote es nullable, pero ya lo tenemos)
        $this->validate();

        DB::beginTransaction();
        try {
            $unidadId = $producto->unidad_id;

            // Crear lote con cantidad inicial = cantidad (opcional: dejar 0 y sumar en finalización)
            $lote = Lote::create([
                'producto_id' => $this->productoId,
                'proveedor_id' => $this->compra->proveedor_id,
                'codigo_lote' => $this->codigoLote,
                'fecha_entrada' => now()->toDateString(),
                'fecha_vencimiento' => $this->fechaVencimiento,
                'cantidad_inicial' => $this->cantidad,      // puedes poner 0 si prefieres
                'cantidad_actual' => $this->cantidad,
                'precio_compra' => $this->precioCompra,
                'estado' => true,
            ]);

            $this->compra->detalleCompras()->create([
                'producto_id' => $producto->id,
                'lote_id' => $lote->id,
                'cantidad' => $this->cantidad,
                'precio_unitario' => $this->precioCompra,
                'subtotal' => $this->cantidad * $this->precioCompra,
                'unidad_id' => $unidadId,
            ]);

            // recalcular total (si quieres hacerlo inmediatamente sobre la relación cargada)
            $this->compra->load('detalleCompras'); // refrescar relación
            $this->compra->total = $this->compra->detalleCompras->sum('subtotal');
            $this->compra->save();

            DB::commit();

            $this->cargarDatos();
            $this->aggItems(); // notificación
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
