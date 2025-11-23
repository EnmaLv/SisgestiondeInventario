<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MovimientoInventario;

class MovimientoInventarioController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $activo = $request->input('estado');

        $query = MovimientoInventario::query();

        // Buscar por lote o producto
        if ($buscar) {
            $query->where(function($q) use ($buscar) {

                // Código de lote
                $q->where('tipo_movimiento', 'like', "%{$buscar}%")->orWhereHas('lote.producto', function($p) use ($buscar){
                    $p->where('nombre', 'like', "%{$buscar}%");
                });

                $q->orWhereHas('lote', function($p) use ($buscar){
                    $p->where('codigo_lote', 'like', "%{$buscar}%");
                });

            });
        }

        // Filtrar por estado
        if ($activo !== null && $activo !== '') {
            $query->where('estado', (int)$activo);
        }

        // Ejecutar consulta
        $movimiento = $query
            ->with(['lote.producto', 'lote.proveedor'])
            ->orderBy('id','desc')
            ->paginate(10);

        return view('admin.movimientos.historial_movimientos.index', compact('movimiento'));
    }
}
