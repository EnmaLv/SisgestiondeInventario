<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CompraProveedorMail;

class CompraController extends Controller
{
    public function index(Request $request)
    {
        $compras = Compra::listarCompras(
            $request->buscar,
            $request->estado
        );

        return view('admin.movimientos.compras.index', compact('compras'));
    }

    public function create()
    {
        $datos = (new Compra())->getDatosFormulario();

        return view('admin.movimientos.compras.create', $datos);
    }


    public function edit($id)
    {
        $compra = Compra::findOrFail($id);

        $datos = $compra->getDatosFormulario();

        return view('admin.movimientos.compras.edit', array_merge($datos, [
            'compra' => $compra
        ]));
    }


    public function store(Request $request)
    {
        $id = Compra::crearCompra($request->validate([
            'proveedor_id' => 'required|exists:proveedors,id',
            'fecha'        => 'required|date',
            'observaciones'=> 'nullable|string'
        ]));

        return redirect()
            ->route('admin.movimientos.compras.edit', $id)
            ->with('success', 'Compra creada exitosamente.');
    }

    public function show($id)
    {
        $compra = Compra::obtenerCompra($id);
        $sucursal_destino = Compra::obtenerSucursalDestino($id);
        $detalles = Compra::obtenerDetallesCompra($id);
        
        return view('admin.movimientos.compras.show', compact('compra', 'sucursal_destino', 'detalles'));
    }

    public function destroy($id)
    {
        $ok = Compra::eliminarCompra($id);

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

        Compra::finalizarCompra($compra, $request->sucursal_id);

        return redirect()
            ->route('admin.movimientos.compras.index')
            ->with('mensaje', 'Compra finalizada.')
            ->with('icono', 'success');
    }

    public function enviarCorreo(Compra $compra)
    {
        $proveedorEmail = $compra->proveedor->email;

        $compra->estado = 'Enviado al proveedor';
        $compra->save();

        Mail::to($proveedorEmail)->send(new CompraProveedorMail($compra));

        return back()->with('mensaje', 'Correo enviado')->with('icono', 'success');
    }
}
