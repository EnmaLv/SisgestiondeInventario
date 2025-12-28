<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pnf;
use App\Models\PersonaPnf;

class PnfController extends Controller
{
    public function index(Request $request)
    {

        $pnfs = Pnf::getPnfs($request);
        return view('admin.maestros.pnf.index', compact('pnfs'));
    }

    public function create()
    {
        return view('admin.maestros.pnf.create');
    }

    public function store(Request $request)
    {
        //Validamos los datos
        $request->validate([
            'nombre' => 'required|string',
            'id_estatus' => 'required|numeric',
        ]);

        Pnf::createPnf($request);

        return redirect()->route('admin.maestros.pnf.index')->with('success', 'PNF creado correctamente');
    }

    public function edit($id)
    {
        $pnf = Pnf::where('id_pnf', $id)->first();
        return view('admin.maestros.pnf.edit', compact('pnf'));
    }

    public function update(Request $request)
    {
        //Validamos los datos
        $request->validate([
            'nombre' => 'required|string',
            'id_estatus' => 'required|numeric',
        ]);

        Pnf::updatePnf($request);

        return redirect()->route('admin.maestros.pnf.index')->with('success', 'PNF actualizado correctamente');
    }

    public function destroy($id)
    {
        //Verificamos que el pnf no tenga personas asociadas
        $personaPnf = PersonaPnf::where('id_pnf', $id)->first();
        if ($personaPnf) {
            return redirect()->route('admin.maestros.pnf.index')->with('error', 'El PNF tiene personas asociadas. No se puede desactivar.');
        }

        $resultado = Pnf::activarPnf($id);
        
        if ($resultado) {
            return redirect()
                ->route('admin.maestros.pnf.index')
                ->with('success', 'Estado del PNF actualizado correctamente');
        }
        return redirect()
            ->route('admin.maestros.pnf.index')
            ->with('error', 'No se pudo actualizar el estado del PNF');
    }

    public function activar($id)
    {
        Pnf::activarPnf($id);

        return redirect()->route('admin.maestros.pnf.index')->with('success', 'PNF activado correctamente');
    }
}
