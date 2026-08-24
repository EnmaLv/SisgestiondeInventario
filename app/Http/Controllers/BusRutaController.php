<?php

namespace App\Http\Controllers;

use App\Models\BusRuta;
use App\Models\BusParada;
use App\Models\BusRutaParada;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'paradas'             => 'nullable|array',
            'paradas.*'           => 'exists:bus_paradas,id',
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
        $paradas    = BusParada::where('estado', 1)->orderBy('nombre')->get();
        return view('admin.transporte.maestros.bus_rutas.create', compact('sucursales', 'paradas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        DB::transaction(function () use ($validated, $request) {
            $ruta = BusRuta::crearRuta($validated);

            if ($request->has('paradas') && is_array($request->paradas)) {
                foreach ($request->paradas as $index => $paradaId) {
                    if (!empty($paradaId)) {
                        BusRutaParada::create([
                            'bus_ruta_id'   => $ruta->id,
                            'bus_parada_id' => $paradaId,
                            'orden'         => $index + 1,
                            'estado'        => 1,
                        ]);
                    }
                }
            }
        });

        return redirect()
            ->route('admin.transporte.maestros.bus_rutas.index')
            ->with('success', 'Ruta registrada correctamente.');
    }

    public function edit(BusRuta $busRuta)
    {
        $busRuta->load('rutaParadas.busParada');
        $sucursales = Sucursal::where('activo', 1)->orderBy('nombre')->get();
        $paradas    = BusParada::where('estado', 1)->orderBy('nombre')->get();
        return view('admin.transporte.maestros.bus_rutas.edit', compact('busRuta', 'sucursales', 'paradas'));
    }

    public function update(Request $request, BusRuta $busRuta)
    {
        $validated = $request->validate($this->rules($busRuta->id));

        DB::transaction(function () use ($busRuta, $validated, $request) {
            BusRuta::actualizarRuta($busRuta, $validated);

            // Sincronizar paradas intermedias
            BusRutaParada::where('bus_ruta_id', $busRuta->id)->delete();

            if ($request->has('paradas') && is_array($request->paradas)) {
                foreach ($request->paradas as $index => $paradaId) {
                    if (!empty($paradaId)) {
                        BusRutaParada::create([
                            'bus_ruta_id'   => $busRuta->id,
                            'bus_parada_id' => $paradaId,
                            'orden'         => $index + 1,
                            'estado'        => 1,
                        ]);
                    }
                }
            }
        });

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

    public function verificarNombre(Request $request)
    {
        $query = BusRuta::where('nombre', trim($request->nombre));
        if ($request->exclude) {
            $query->where('id', '!=', $request->exclude);
        }
        return response()->json(['existe' => $query->exists()]);
    }
}