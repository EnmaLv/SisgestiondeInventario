<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\BusModelo;

class BusModeloApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = BusModelo::with('marca')->orderBy('nombre');

        if ($request->filled('marca_id')) {
            $query->where('bus_marca_id', $request->integer('marca_id'));
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->boolean('estado') ? 1 : 0);
        }

        return response()->json(['success' => true, 'data' => $query->get()]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'bus_marca_id' => 'required|integer|exists:bus_marca,id',
            'nombre'       => 'required|string|max:100',
        ]);

        // Nombre único por marca
        $existe = BusModelo::where('bus_marca_id', $validated['bus_marca_id'])
            ->where('nombre', ucfirst(trim($validated['nombre'])))
            ->exists();

        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe ese modelo para esta marca.',
            ], 422);
        }

        $modelo = BusModelo::create([
            'bus_marca_id' => $validated['bus_marca_id'],
            'nombre'       => ucfirst(trim($validated['nombre'])),
            'estado'       => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Modelo creado correctamente.',
            'data'    => $modelo->load('marca'),
        ], 201);
    }

    public function show(BusModelo $modelo): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $modelo->load('marca')]);
    }

    public function update(Request $request, BusModelo $modelo): JsonResponse
    {
        $validated = $request->validate([
            'bus_marca_id' => 'required|integer|exists:bus_marca,id',
            'nombre'       => 'required|string|max:100',
        ]);

        $modelo->update([
            'bus_marca_id' => $validated['bus_marca_id'],
            'nombre'       => ucfirst(trim($validated['nombre'])),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Modelo actualizado.',
            'data'    => $modelo->fresh()->load('marca'),
        ]);
    }

    public function toggle(BusModelo $modelo): JsonResponse
    {
        $modelo->update(['estado' => $modelo->estado ? 0 : 1]);

        return response()->json([
            'success' => true,
            'message' => $modelo->estado ? 'Modelo activado.' : 'Modelo desactivado.',
            'data'    => $modelo->fresh()->load('marca'),
        ]);
    }

    public function destroy(BusModelo $modelo): JsonResponse
    {
        // Verificar que no tenga vehículos asociados
        if ($modelo->vehiculos()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar: tiene vehículos asociados.',
            ], 422);
        }

        $modelo->delete();

        return response()->json(['success' => true, 'message' => 'Modelo eliminado.']);
    }
}
