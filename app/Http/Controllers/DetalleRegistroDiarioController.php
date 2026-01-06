<?php

namespace App\Http\Controllers;

use App\Models\SobranteComedor;
use App\Models\Receta;

class DetalleRegistroDiarioController extends Controller
{
    public function index()
    {
        $comidas = Receta::orderBy('id', 'desc')->where('estado', true)->get();
        $sobrantes = SobranteComedor::paginate(10);
        return view('admin.movimientos.registro_comida.index', compact('comidas', 'sobrantes'));
    }
}
