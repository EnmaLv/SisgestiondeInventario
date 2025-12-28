<?php

namespace App\Http\Controllers;

use App\Models\InventarioSucursalLote;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class InventarioSucursalLoteController extends Controller
{
    public function index()
    {
        $sucursales = Sucursal::withCount('inventarioSucursalLotes')->get();

        foreach ($sucursales as $sucursal) {
            $sucursal->totalInventarioSucursalLotes = InventarioSucursalLote::where('sucursal_id', $sucursal->id)->get()->sum('cantidad');
        }

        return view('admin.movimientos.sucursales_lotes.index', compact('sucursales'));
    }

    public function show($id, Request $request)
    {
        $buscar = $request->input('buscar');
        $activo = $request->input('estado');

        $query = InventarioSucursalLote::query();

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

        // Filtrar por sucursal específica
        $query->where('sucursal_id', $id);

        // Ejecutar consulta
        $sucursal = $query
            ->with(['lote.producto', 'lote.proveedor'])
            ->orderBy('id','desc')
            ->paginate(10);

        $sucursales = Sucursal::withCount('inventarioSucursalLotes')->get();
        
        return view('admin.movimientos.sucursales_lotes.show', compact('sucursal', 'sucursales'));
    }
}
