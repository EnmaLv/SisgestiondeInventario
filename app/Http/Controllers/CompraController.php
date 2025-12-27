<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Lote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CompraProveedorMail;
use App\Utilities\PdfGeneratorUtil;

/**
 * Controlador para gestionar las operaciones relacionadas con las compras
 * a proveedores en el sistema de gestión de inventario.
 */
class CompraController extends Controller
{
    /**
     * Muestra el listado de compras con opciones de búsqueda y filtrado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Obtiene las compras aplicando filtros de búsqueda y estado
        $compras = Compra::listarCompras(
            $request->buscar,  // Término de búsqueda opcional
            $request->estado   // Filtro de estado opcional
        );

        return view('admin.movimientos.compras.index', compact('compras'));
    }

    /**
     * Muestra el formulario para crear una nueva compra.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Obtiene los datos necesarios para el formulario de creación
        $datos = (new Compra())->getDatosFormulario();

        return view('admin.movimientos.compras.create', $datos);
    }


    /**
     * Muestra el formulario para editar una compra existente.
     *
     * @param  int  $id  ID de la compra a editar
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Busca la compra o falla con error 404 si no existe
        $compra = Compra::with(['detalleCompras' => function ($query) {
            $query->with(['producto', 'lote']); // Cargar relación con producto y lote
        }, 'proveedor'])->findOrFail($id);

        // Obtiene los datos necesarios para el formulario de edición
        $datos = $compra->getDatosFormulario();

        return view('admin.movimientos.compras.edit', array_merge($datos, [
            'compra' => $compra  // Pasa la compra a la vista
        ]));
    }


    /**
     * Almacena una nueva compra en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Valida los datos del formulario y crea la compra
        $id = Compra::crearCompra($request->validate([
            'proveedor_id' => 'required|exists:proveedors,id',  // ID del proveedor requerido
            'fecha'        => 'required|date',                   // Fecha de la compra requerida
            'observaciones' => 'nullable|string'                  // Observaciones opcionales
        ]));

        // Redirige al formulario de edición de la compra recién creada
        return redirect()->route('admin.movimientos.compras.edit', $id)->with('success', 'Compra creada exitosamente.');
    }

    /**
     * Muestra los detalles de una compra específica.
     *
     * @param  int  $id  ID de la compra a mostrar
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Obtiene la información detallada de la compra
        $compra = Compra::obtenerCompra($id);
        $sucursal_destino = Compra::obtenerSucursalDestino($id);
        $detalles = Compra::obtenerDetallesCompra($id);

        return view('admin.movimientos.compras.show', compact('compra', 'sucursal_destino', 'detalles'));
    }

    /**
     * Elimina una compra del sistema.
     *
     * @param  int  $id  ID de la compra a eliminar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Intenta eliminar la compra y obtiene el resultado
        $ok = Compra::eliminarCompra($id);

        // Redirige al listado de compras con un mensaje de éxito o error
        return redirect()
            ->route('admin.movimientos.compras.index')
            ->with('mensaje', $ok ? 'Compra eliminada.' : 'Error al eliminar.')
            ->with('icono', $ok ? 'success' : 'error');
    }

    public function finalizarCompra(Request $request, Compra $compra)
    {
        $request->validate([
            'sucursal_id' => 'required|exists:sucursals,id',
        ]);
        $lotesSinFecha = Lote::whereIn(
            'id',
            $compra->detalleCompras->pluck('lote_id')
        )
        ->whereNull('fecha_vencimiento')
        ->exists();

        if ($lotesSinFecha) {
            return back()->withErrors([
                'fecha_vencimiento' =>
                    'Debe registrar la fecha de vencimiento de todos los productos antes de finalizar la requisición.'
            ]);
        }
        Compra::finalizarCompra($compra, $request->sucursal_id);

        return redirect()->route('admin.movimientos.compras.index')->with('success', 'Requisición finalizada correctamente.');
    }
    
    public function cancelar(Compra $compra)
    {
        if ($compra->estado === 'Finalizada') {
            return redirect()
                ->route('admin.movimientos.compras.index')
                ->with('error', 'No se puede cancelar una requisición finalizada.');
        }
        $ok = Compra::eliminarCompra($compra->id);

        return redirect()
            ->route('admin.movimientos.compras.index')
            ->with(
                $ok ? 'success' : 'error',
                $ok
                    ? 'Requisición cancelada. No se guardaron los productos.'
                    : 'No se pudo cancelar la requisición.'
            );
    }

    public function enviarCorreo(Compra $compra)
    {
        // Obtiene el correo electrónico del proveedor
        $proveedorEmail = $compra->proveedor->email;

        // Actualiza el estado de la compra
        $compra->estado = 'Enviado al proveedor';
        $compra->save();

        // Envía el correo electrónico al proveedor
        Mail::to($proveedorEmail)->send(new CompraProveedorMail($compra));

        // Redirige a la página anterior con un mensaje de éxito
        return back()->with('success', 'Correo enviado exitosamente.');
    }



    public function exportPdf(Request $request)
    {
        // 1. Obtener la lista plana de todos los detalles desde tu función estática
        $itemsPlano = Compra::obtenerTodosDetallesCompras(
            $request->buscar,
            $request->estado
        );

        // Si la colección está vacía, no hay nada que reportar
        if ($itemsPlano->isEmpty()) {
            return back()->with('error', 'No se encontraron datos para el reporte.');
        }

        // 2. AGRUPAR los ítems por el campo 'compra_id'
        // Esto crea una colección donde la clave es el ID de la compra (ej: '3') 
        // y el valor es otra colección con todos los ítems que pertenecen a ese ID.
        $comprasAgrupadas = $itemsPlano->groupBy('compra_id');

        // 3. TRANSFORMAR la colección agrupada a un formato de Compra principal con sus Detalles
        $compras = $comprasAgrupadas->map(function ($items, $compraId) {
            // Tomamos el primer ítem para obtener los datos que son comunes (cabecera de la compra)
            $primerItem = $items->first();

            // Calculamos el total de la compra sumando los subtotales de sus ítems
            $totalCompra = $items->sum('subtotal');

            // Estructura final que la vista Blade puede iterar
            return (object) [
                'id'                  => $compraId,
                'fecha'               => $primerItem->fecha,
                'proveedor_empresa'   => $primerItem->proveedor_empresa,
                'created_at'          => $primerItem->created_at,
                'total'               => $totalCompra,
                'observaciones'       => null,
                'detalles'            => $items->all()
            ];
        })->values();

        $datos = [
            'compras' => $compras, // ¡Esta es la colección agrupada que usará tu vista!
            'buscar' => $request->buscar,
            'estado' => $request->estado
        ];

        // dd($compras);

        return PdfGeneratorUtil::ShowPdf('pdf.compra', $datos, "Compras");
    }
}
