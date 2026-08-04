<?php

namespace App\Http\Controllers;

use App\Models\InventarioSedeLote;
use App\Models\Sede;
use Illuminate\Http\Request;

class InventarioSedeLoteController extends Controller
{
    public function index()
    {
        $sedes = Sede::withCount('inventarioSedeLotes')->get();

        foreach ($sedes as $sede) {
            $sede->totalInventarioSedeLotes = InventarioSedeLote::where('sede_id', $sede->id)->sum('cantidad');
        }

        return view('admin.movimientos.sedes_lotes.index', compact('sedes'));
    }

    public function show($id, Request $request)
    {
        $buscar = $request->input('buscar');
        $activo = $request->input('estado');

        $query = InventarioSedeLote::query();

        // Buscar por lote o producto
        if ($buscar) {
            $query->where(function($q) use ($buscar) {
                // Código de lote
                $q->whereHas('lote', function($l) use ($buscar){
                    $l->where('codigo_lote', 'like', "%{$buscar}%");
                });

                // Nombre del producto
                $q->orWhereHas('lote.producto', function($p) use ($buscar){
                    $p->where('nombre', 'like', "%{$buscar}%");
                });
            });
        }

        // Filtrar por estado
        if ($activo !== null && $activo !== '') {
            $query->where('estado', (int)$activo);
        }

        // Filtrar por sede específica
        $query->where('sede_id', $id);

        // Ejecutar consulta
        $sede = $query
            ->with(['lote.producto', 'lote.proveedor'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        $sedes = Sede::withCount('inventarioSedeLotes')->get();

        return view('admin.movimientos.sedes_lotes.show', compact('sede', 'sedes', 'buscar'));
    }
}