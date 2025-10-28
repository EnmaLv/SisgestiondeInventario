<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro_diario;
use App\Models\Persona; 

class RegistroDiarioController extends Controller
{
    public function index()
    {
        return view('admin.movimientos.registro_diario.index');
    }

    //Funcion para verrificar si la persona esta en la bd
    public function verificarPersona(Request $request)
    {
        return;
    }

    public function buscar(Request $request)
    {
        return;
    }
}
