<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MovimientoInventario;

class MovimientoInventarioController extends Controller
{
    public function index()
    {
        $movimientos = MovimientoInventario::all();
        return view('admin.movimientos.historial_movimientos.index', compact('movimientos'));
    }
}
