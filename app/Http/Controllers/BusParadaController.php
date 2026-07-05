<?php

namespace App\Http\Controllers;

use App\Models\BusParada;
use Illuminate\Http\Request;

class BusParadaController extends Controller
{
    private function rules(int $excludeId = null): array
    {
        return [
            'nombre'    => 'required|string|max:100|unique:bus_paradas,nombre,' . $excludeId,
            'lat'       => 'nullable|numeric|between:-90,90',
            'lng'       => 'nullable|numeric|between:-180,180',
            'direccion' => 'nullable|string|max:255',
        ];
    }

    public function index(Request $request)
    {
        $paradas = BusParada::listarParadas($request->buscar, $request->input('estado', 1));
        return view('admin.transporte.maestros.bus_paradas.index', compact('paradas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());
        $parada = BusParada::crearParada($validated);
        return response()->json([
            'success' => true,
            'message' => 'Parada registrada correctamente.',
            'parada'  => [
                'id'        => $parada->id,
                'nombre'    => $parada->nombre,
                'lat'       => $parada->lat,
                'lng'       => $parada->lng,
                'direccion' => $parada->direccion,
                'estado'    => true,
            ],
        ]);
    }

    public function update(Request $request, BusParada $busParada)
    {
        $validated = $request->validate($this->rules($busParada->id));
        BusParada::actualizarParada($busParada, $validated);
        $busParada->refresh();
        return response()->json([
            'success' => true,
            'message' => 'Parada actualizada correctamente.',
            'parada'  => [
                'id'        => $busParada->id,
                'nombre'    => $busParada->nombre,
                'lat'       => $busParada->lat,
                'lng'       => $busParada->lng,
                'direccion' => $busParada->direccion,
                'estado'    => $busParada->estado,
            ],
        ]);
    }

    public function destroy(BusParada $busParada)
    {
        $busParada->update(['estado' => 0]);
        return response()->json(['success' => true, 'message' => 'Parada inactivada correctamente.']);
    }

    public function activar(BusParada $busParada)
    {
        $busParada->update(['estado' => 1]);
        return response()->json(['success' => true, 'message' => 'Parada activada correctamente.']);
    }

    public function verificarNombre(Request $request)
    {
        $query = BusParada::where('nombre', trim($request->nombre));
        if ($request->exclude) {
            $query->where('id', '!=', $request->exclude);
        }
        return response()->json(['existe' => $query->exists()]);
    }
}