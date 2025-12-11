<?php

namespace App\Http\Controllers;

use App\Models\DetalleRegistroDiario;
use App\Models\Receta;
use Illuminate\Http\Request;

class DetalleRegistroDiarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comidas = Receta::orderBy('id', 'desc')->where('estado', true)->get();
        return view('admin.movimientos.registro_comida.index', compact('comidas'));
    }
}
