<?php

namespace App\Http\Controllers;

use App\Models\BusViaje;
use App\Models\BusVehiculo;
use App\Models\BusRuta;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    private function getConductores()
    {
        $conductores = Usuario::with('persona')
            ->whereHas('roles', fn ($q) => $q->where('nombre', 'like', '%conductor%'))
            ->get();

        if ($conductores->isEmpty()) {
            $conductores = Usuario::with('persona')->orderBy('username')->get();
        }

        return $conductores;
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
        $conductores = $this->getConductores();
        return view('admin.transporte.maestros.bus_viajes.create',
            compact('vehiculos', 'rutas', 'conductores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        if (!empty($validated['km_fin']) && $validated['km_fin'] > $validated['km_inicio']) {
            $validated['distancia_km'] = $validated['km_fin'] - $validated['km_inicio'];
        }

        DB::transaction(function () use ($validated) {
            $viaje = BusViaje::crearViaje($validated);
            $vehiculo = BusVehiculo::find($validated['bus_vehiculo_id']);

            if ($vehiculo) {
                if ($validated['estado'] === 'en_curso') {
                    $vehiculo->update(['estado' => 'en_ruta']);
                } elseif ($validated['estado'] === 'finalizado') {
                    $newKm = (!empty($validated['km_fin']) && $validated['km_fin'] > $vehiculo->km_actual)
                        ? $validated['km_fin']
                        : $vehiculo->km_actual;
                    $vehiculo->update(['estado' => 'disponible', 'km_actual' => $newKm]);
                }
            }
        });

        return redirect()
            ->route('admin.transporte.maestros.bus_viajes.index')
            ->with('success', 'Viaje registrado correctamente.');
    }

    public function edit(BusViaje $busViaje)
    {
        $vehiculos   = BusVehiculo::where('activo', 1)->orderBy('placa')->get();
        $rutas       = BusRuta::where('estado', 1)->orderBy('nombre')->get();
        $conductores = $this->getConductores();
        return view('admin.transporte.maestros.bus_viajes.edit',
            compact('busViaje', 'vehiculos', 'rutas', 'conductores'));
    }

    public function update(Request $request, BusViaje $busViaje)
    {
        $validated = $request->validate($this->rules($busViaje->id));

        if (!empty($validated['km_fin']) && $validated['km_fin'] > $validated['km_inicio']) {
            $validated['distancia_km'] = $validated['km_fin'] - $validated['km_inicio'];
        }

        DB::transaction(function () use ($busViaje, $validated) {
            BusViaje::actualizarViaje($busViaje, $validated);
            $vehiculo = BusVehiculo::find($validated['bus_vehiculo_id']);

            if ($vehiculo) {
                if ($validated['estado'] === 'en_curso') {
                    $vehiculo->update(['estado' => 'en_ruta']);
                } elseif ($validated['estado'] === 'finalizado') {
                    $newKm = (!empty($validated['km_fin']) && $validated['km_fin'] > $vehiculo->km_actual)
                        ? $validated['km_fin']
                        : $vehiculo->km_actual;
                    $vehiculo->update(['estado' => 'disponible', 'km_actual' => $newKm]);
                } elseif ($validated['estado'] === 'cancelado') {
                    $vehiculo->update(['estado' => 'disponible']);
                }
            }
        });

        return redirect()
            ->route('admin.transporte.maestros.bus_viajes.index')
            ->with('success', 'Viaje actualizado correctamente.');
    }

    public function destroy(BusViaje $busViaje)
    {
        $busViaje->update(['estado' => 'cancelado']);
        return redirect()
            ->route('admin.transporte.maestros.bus_viajes.index')
            ->with('success', 'Viaje cancelado correctamente.');
    }
}