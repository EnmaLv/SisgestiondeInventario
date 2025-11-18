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
        /* response()->json($sucursalesLotes); */
    }

    public function mostrar_sucursal_lote($id)
    {
        $sucursal = InventarioSucursalLote::where('sucursal_id', $id)->with('lote.producto', 'lote.proveedor')->get();
        return view('admin.movimientos.sucursales_lotes.show', compact('sucursal'));
    }
}
