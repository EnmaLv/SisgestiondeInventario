<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventarioSucursalLoteController extends Controller
{
    public function index()
    {
        // Lógica para obtener y mostrar el inventario por sucursal y lote
        $sucursalesLotes = \App\Models\InventarioSucursalLote::with('lote.producto', 'sucursal')->get(); 
        return view('admin.movimientos.sucursales_lotes.index', compact('sucursalesLotes'));
        /* response()->json($sucursalesLotes); */
    }
}
