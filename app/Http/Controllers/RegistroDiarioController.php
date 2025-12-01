<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro_diario;
use App\Models\Receta;
use App\Models\Persona; 
use App\Utilities\PdfGeneratorUtil;
use App\Exports\RegistroDiarioExport;
use Maatwebsite\Excel\Facades\Excel;

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

        $comidas = Receta::all();
        return view('admin.movimientos.registro_diario.index', compact('data', 'comidas'));
    }

    public function show($id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'ID no válido');
        }
        
        $registro = Registro_diario::getRegister($id);
        if (!$registro) {
            return redirect()->back()->with('error', 'Registro no encontrado');
        }
        
        return view('admin.movimientos.registro_diario.show', compact('registro'));
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

    //Reportes
    public function exportPdf(Request $request)
    {
        $filter = [];

        $Hoy = date('Y-m-d');

        //Recuperamos la informacion de la fecha desde y hasta
        $fecha_desde = $request->input('fecha_desde') == "" ?  date('Y-m-d', strtotime('-1 month')) : $request->input('fecha_desde');
        $fecha_hasta = $request->input('fecha_hasta') == "" ? $Hoy : $request->input('fecha_hasta');


        $filter = [
            'fecha_desde'=> $fecha_desde,
            'fecha_hasta'=> $fecha_hasta,
        ];



        $register = Registro_diario::showData($filter, true);

        $datos = [
            'registros' => $register,
            'fecha_desde' => $fecha_desde,
            'fecha_hasta' => $fecha_hasta
        ]; 

        return PdfGeneratorUtil::ShowPdf('pdf.registro_diario',$datos , "Registro Diario");
    }   

    public function exportExcel(Request $request)
    {

        $fileName = "registro_diario";
        //Verificamos primero si resivimos algun filtro de la peticion
        if(!$request->all()){

            return Excel::download(new RegistroDiarioExport([]), $fileName . ".xlsx");
        }

        $filtros = [
            'fecha_desde' => $request->input('fecha_desde'),
            'fecha_hasta' => $request->input('fecha_hasta'),
            'buscar' => $request->input('buscar')
        ];

        return Excel::download(new RegistroDiarioExport($filtros), $fileName . "_" . $filtros['fecha_desde'] . "_" . $filtros['fecha_hasta'] . ".xlsx");



    }
}
