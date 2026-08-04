<?php

namespace App\Http\Controllers;

use App\Models\BusMarca;
use Illuminate\Http\Request;

class BusMarcaController extends Controller
{
    public function index(Request $request)
    {
        $marcas = BusMarca::listarMarcas($request->buscar, $request->input('estado', 1));
        return view('admin.transporte.maestros.bus_marcas.index', compact('marcas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:100|unique:marcas,nombre',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $marca = BusMarca::crearMarca($validated);

        return response()->json([
            'success' => true,
            'message' => 'Marca registrada correctamente.',
            'marca'   => [
                'id'          => $marca->id,
                'nombre'      => $marca->nombre,
                'descripcion' => $marca->descripcion,
                'estado'      => true,
            ],
        ]);
    }

    public function update(Request $request, BusMarca $busMarca)
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:100|unique:marcas,nombre,' . $busMarca->id,
            'descripcion' => 'nullable|string|max:255',
        ]);

        BusMarca::actualizarMarca($busMarca, $validated);
        $busMarca->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Marca actualizada correctamente.',
            'marca'   => [
                'id'          => $busMarca->id,
                'nombre'      => $busMarca->nombre,
                'descripcion' => $busMarca->descripcion,
                'estado'      => $busMarca->estado,
            ],
        ]);
    }

    public function destroy(BusMarca $busMarca)
    {
        $busMarca->update(['estado' => 0]);
        return response()->json(['success' => true, 'message' => 'Marca inactivada correctamente.']);
    }

    public function activar(BusMarca $busMarca)
    {
        $busMarca->update(['estado' => 1]);
        return response()->json(['success' => true, 'message' => 'Marca activada correctamente.']);
    }
}