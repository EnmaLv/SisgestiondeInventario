<?php

namespace App\Http\Controllers;

use App\Models\BusModelo;
use App\Models\BusMarca;
use Illuminate\Http\Request;

class BusModeloController extends Controller
{
    public function index(Request $request)
    {
        $modelos = BusModelo::listarModelos($request->buscar, $request->input('estado', 1));
        $marcas  = BusMarca::where('estado', 1)->orderBy('nombre')->get();

        return view('admin.transporte.maestros.bus_modelos.index', compact('modelos', 'marcas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_marca_id' => 'required|exists:bus_marcas,id',
            'nombre'       => 'required|string|max:100|unique:bus_modelos,nombre',
            'descripcion'  => 'nullable|string|max:255',
        ]);

        $modelo = BusModelo::crearModelo($validated);
        $modelo->load('busMarca');

        return response()->json([
            'success' => true,
            'message' => 'Modelo registrado correctamente.',
            'modelo'  => [
                'id'          => $modelo->id,
                'nombre'      => $modelo->nombre,
                'descripcion' => $modelo->descripcion,
                'marca_id'    => $modelo->bus_marca_id,
                'marca_nombre'=> $modelo->busMarca->nombre,
                'estado'      => true,
            ],
        ]);
    }

    public function update(Request $request, BusModelo $busModelo)
    {
        $validated = $request->validate([
            'bus_marca_id' => 'required|exists:bus_marcas,id',
            'nombre'       => 'required|string|max:100|unique:bus_modelos,nombre,' . $busModelo->id,
            'descripcion'  => 'nullable|string|max:255',
        ]);

        BusModelo::actualizarModelo($busModelo, $validated);
        $busModelo->refresh()->load('busMarca');

        return response()->json([
            'success' => true,
            'message' => 'Modelo actualizado correctamente.',
            'modelo'  => [
                'id'          => $busModelo->id,
                'nombre'      => $busModelo->nombre,
                'descripcion' => $busModelo->descripcion,
                'marca_id'    => $busModelo->bus_marca_id,
                'marca_nombre'=> $busModelo->busMarca->nombre,
                'estado'      => $busModelo->estado,
            ],
        ]);
    }

    public function destroy(BusModelo $busModelo)
    {
        $busModelo->update(['estado' => 0]);
        return response()->json(['success' => true, 'message' => 'Modelo inactivado correctamente.']);
    }

    public function activar(BusModelo $busModelo)
    {
        $busModelo->update(['estado' => 1]);
        return response()->json(['success' => true, 'message' => 'Modelo activado correctamente.']);
    }
}