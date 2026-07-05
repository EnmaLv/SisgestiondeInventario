<?php

namespace App\Http\Controllers;

use App\Models\BusTipoCombustible;
use Illuminate\Http\Request;

class BusTipoCombustibleController extends Controller
{
    public function index(Request $request)
    {
        $tipos = BusTipoCombustible::listarTipos($request->buscar, $request->input('estado', 1));
        return view('admin.transporte.maestros.bus_tipo_combustibles.index', compact('tipos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:100|unique:bus_tipo_combustibles,nombre',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $tipo = BusTipoCombustible::crearTipo($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tipo de combustible registrado correctamente.',
            'tipo'    => [
                'id'          => $tipo->id,
                'nombre'      => $tipo->nombre,
                'descripcion' => $tipo->descripcion,
                'estado'      => true,
            ],
        ]);
    }

    public function update(Request $request, BusTipoCombustible $busTipoCombustible)
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:100|unique:bus_tipo_combustibles,nombre,' . $busTipoCombustible->id,
            'descripcion' => 'nullable|string|max:255',
        ]);

        BusTipoCombustible::actualizarTipo($busTipoCombustible, $validated);
        $busTipoCombustible->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Tipo de combustible actualizado correctamente.',
            'tipo'    => [
                'id'          => $busTipoCombustible->id,
                'nombre'      => $busTipoCombustible->nombre,
                'descripcion' => $busTipoCombustible->descripcion,
                'estado'      => $busTipoCombustible->estado,
            ],
        ]);
    }

    public function destroy(BusTipoCombustible $busTipoCombustible)
    {
        $busTipoCombustible->update(['estado' => 0]);
        return response()->json(['success' => true, 'message' => 'Tipo de combustible inactivado correctamente.']);
    }

    public function activar(BusTipoCombustible $busTipoCombustible)
    {
        $busTipoCombustible->update(['estado' => 1]);
        return response()->json(['success' => true, 'message' => 'Tipo de combustible activado correctamente.']);
    }
}