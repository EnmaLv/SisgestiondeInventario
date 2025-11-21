<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro_diario;
use App\Models\Persona; 

class RegistroDiarioController extends Controller
{
    public function index(Request $request)
    {
        //Recibir el valor del input
        $buscar = $request->input("buscar");

        $fecha_desde = $request->input("fecha_desde");
        $fecha_hasta = $request->input("fecha_hasta");

        $filter = [
            "fecha_desde" => $fecha_desde ?? null,
            "fecha_hasta" => $fecha_hasta ?? null,
            "buscar"=> $buscar ?? null
        ];



        if ($filter != null) {
            $data = Registro_diario::showData($filter);
        }else{

            $data = Registro_diario::showData();
        }
        return view('admin.movimientos.registro_diario.index', compact('data'));
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
