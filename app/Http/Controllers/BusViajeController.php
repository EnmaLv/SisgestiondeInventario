<?php

namespace App\Http\Controllers;

use App\Models\BusViaje;
use App\Models\BusVehiculo;
use App\Models\BusRuta;
use App\Models\Usuario;
use Illuminate\Http\Request;

class BusViajeController extends Controller
{
    private function rules(int $excludeId = null): array
    {
        return [
            'bus_vehiculo_id' => 'required|exists:bus_vehiculos,id',
            'bus_ruta_id'     => 'required|exists:bus_rutas,id',
            'conductor_id'    => 'nullable|exists:usuario,id_usuario',
            'turno'           => 'nullable|in:mañana,tarde,noche',
            'firebase_id'     => 'nullable|string|max:100',
            'fecha_inicio'    => 'nullable|date',
            'fecha_fin'       => 'nullable|date|after_or_equal:fecha_inicio',
            'km_inicio'       => 'required|numeric|min:0|max:9999999',
            'km_fin'          => 'nullable|numeric|min:0|max:9999999',
            'distancia_km'    => 'nullable|numeric|min:0|max:9999',
            'litros_gastados' => 'nullable|numeric|min:0|max:9999',
            'pasajeros'       => 'nullable|integer|min:0|max:300',
            'observaciones'   => 'nullable|string|max:2000',
            'estado'          => 'required|in:programado,en_curso,finalizado,cancelado',
        ];
    }

    public function index(Request $request)
    {
        $viajes = BusViaje::listarViajes($request->buscar, $request->input('estado'));
        return view('admin.transporte.maestros.bus_viajes.index', compact('viajes'));
    }

    public function create()
    {
        $vehiculos   = BusVehiculo::where('activo', 1)->orderBy('placa')->get();
        $rutas       = BusRuta::where('estado', 1)->orderBy('nombre')->get();
        $conductores = Usuario::whereHas('roles', fn ($q) => $q->where('nombre', 'like', '%conductor%'))
            ->orderBy('username')->get();
        return view('admin.transporte.maestros.bus_viajes.create',
            compact('vehiculos', 'rutas', 'conductores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());
        BusViaje::crearViaje($validated);
        return redirect()
            ->route('admin.transporte.maestros.bus_viajes.index')
            ->with('success', 'Viaje registrado correctamente.');
    }

    public function edit(BusViaje $busViaje)
    {
        $vehiculos   = BusVehiculo::where('activo', 1)->orderBy('placa')->get();
        $rutas       = BusRuta::where('estado', 1)->orderBy('nombre')->get();
        $conductores = Usuario::whereHas('roles', fn ($q) => $q->where('nombre', 'like', '%conductor%'))
            ->orderBy('username')->get();
        return view('admin.transporte.maestros.bus_viajes.edit',
            compact('busViaje', 'vehiculos', 'rutas', 'conductores'));
    }

    public function update(Request $request, BusViaje $busViaje)
    {
        $validated = $request->validate($this->rules($busViaje->id));
        BusViaje::actualizarViaje($busViaje, $validated);
        return redirect()
            ->route('admin.transporte.maestros.bus_viajes.index')
            ->with('success', 'Viaje actualizado correctamente.');
    }

    public function destroy(BusViaje $busViaje)
    {
        $busViaje->delete();
        return redirect()
            ->route('admin.transporte.maestros.bus_viajes.index')
            ->with('success', 'Viaje eliminado correctamente.');
    }
}