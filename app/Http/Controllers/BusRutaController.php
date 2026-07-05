<?php

namespace App\Http\Controllers;

use App\Models\BusRuta;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class BusRutaController extends Controller
{
    private function rules(int $excludeId = null): array
    {
        return [
            'nombre'              => 'required|string|max:100|unique:bus_rutas,nombre,' . $excludeId,
            'distancia_km'        => 'required|numeric|min:0.1|max:9999',
            'hora_salida_manana'  => 'nullable|date_format:H:i',
            'hora_salida_tarde'   => 'nullable|date_format:H:i',
            'hora_salida_noche'   => 'nullable|date_format:H:i',
            'descripcion'         => 'nullable|string|max:1000',
            'sucursal_origen_id'  => 'required|exists:sucursals,id',
            'sucursal_destino_id' => 'required|exists:sucursals,id',
        ];
    }

    public function index(Request $request)
    {
        $rutas = BusRuta::listarRutas($request->buscar, $request->input('estado', 1));
        return view('admin.transporte.maestros.bus_rutas.index', compact('rutas'));
    }

    public function create()
    {
        $sucursales = Sucursal::where('activo', 1)->orderBy('nombre')->get();
        return view('admin.transporte.maestros.bus_rutas.create', compact('sucursales'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());
        BusRuta::crearRuta($validated);
        return redirect()
            ->route('admin.transporte.maestros.bus_rutas.index')
            ->with('success', 'Ruta registrada correctamente.');
    }

    public function edit(BusRuta $busRuta)
    {
        $sucursales = Sucursal::where('activo', 1)->orderBy('nombre')->get();
        return view('admin.transporte.maestros.bus_rutas.edit', compact('busRuta', 'sucursales'));
    }

    public function update(Request $request, BusRuta $busRuta)
    {
        $validated = $request->validate($this->rules($busRuta->id));
        BusRuta::actualizarRuta($busRuta, $validated);
        return redirect()
            ->route('admin.transporte.maestros.bus_rutas.index')
            ->with('success', 'Ruta actualizada correctamente.');
    }

    public function destroy(BusRuta $busRuta)
    {
        $busRuta->update(['estado' => 0]);
        return redirect()
            ->route('admin.transporte.maestros.bus_rutas.index')
            ->with('success', 'Ruta inactivada correctamente.');
    }

    public function activar(BusRuta $busRuta)
    {
        $busRuta->update(['estado' => 1]);
        return redirect()
            ->route('admin.transporte.maestros.bus_rutas.index')
            ->with('success', 'Ruta activada correctamente.');
    }
}