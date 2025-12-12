<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro_diario;
use App\Models\Receta;
use App\Models\Persona;
use App\Utilities\PdfGeneratorUtil;
use App\Exports\RegistroDiarioExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\DetalleRegistroDiario;

class RegistroDiarioController extends Controller
{
    public function index(Request $request)
    {
        //No dejar que se vea la pagina sin un desayuno registrado
        // $hayReceta = Receta::first("id");

        // if (!$hayReceta) {
        //     return redirect()->back()->with('mensaje', 'No hay recetas registradas')
        //     ->with('icono', 'error')
        //     ->with('texto', 'No hay recetas registradas, Intenta agregar una receta.');
        // }

        //Recibir el valor del input
        $buscar = $request->input("buscar");

        $fecha_desde = $request->input("fecha_desde");
        $fecha_hasta = $request->input("fecha_hasta");

        $filter = [
            "fecha_desde" => $fecha_desde ?? null,
            "fecha_hasta" => $fecha_hasta ?? null,
            "buscar" => $buscar ?? null
        ];



        //Enviamos si hay un desayuno registrado para ese dia
        $receta_diario = DetalleRegistroDiario::whereDate('created_at', now())->exists();

        if ($filter != null) {
            $data = Registro_diario::showData($filter);
        } else {

            $data = Registro_diario::showData();
        }

        return view('admin.movimientos.registro_diario.index', compact('data', 'receta_diario'));
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
            'fecha_desde' => $fecha_desde,
            'fecha_hasta' => $fecha_hasta,
        ];



        $register = Registro_diario::showData($filter, true);

        $datos = [
            'registros' => $register,
            'fecha_desde' => $fecha_desde,
            'fecha_hasta' => $fecha_hasta
        ];

        return PdfGeneratorUtil::ShowPdf('pdf.registro_diario', $datos, "Registro Diario");
    }

    public function exportExcel(Request $request)
    {

        $fileName = "registro_diario";
        //Verificamos primero si resivimos algun filtro de la peticion
        if (!$request->all()) {

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
