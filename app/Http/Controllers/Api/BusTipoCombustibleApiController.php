<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\BusTipoCombustible;

class BusTipoCombustibleApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = BusTipoCombustible::orderBy('nombre');

        if ($request->has('estado')) {
            $query->where('estado', $request->boolean('estado') ? 1 : 0);
        }

        return response()->json([
            'success' => true,
            'data'    => $query->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:100|unique:tipo_combustibles,nombre',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $tipo = BusTipoCombustible::create([
            'nombre'      => ucfirst(trim($validated['nombre'])),
            'descripcion' => $validated['descripcion'] ?? null,
            'estado'      => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tipo de combustible creado correctamente.',
            'data'    => $tipo,
        ], 201);
    }

    public function show(BusTipoCombustible $combustible): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $combustible,
        ]);
    }

    public function update(Request $request, BusTipoCombustible $combustible): JsonResponse
    {
        $validated = $request->validate([
            'nombre'      => "required|string|max:100|unique:tipo_combustibles,nombre,{$combustible->id}",
            'descripcion' => 'nullable|string|max:255',
        ]);

        $combustible->update([
            'nombre'      => ucfirst(trim($validated['nombre'])),
            'descripcion' => $validated['descripcion'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tipo de combustible actualizado correctamente.',
            'data'    => $combustible->fresh(),
        ]);
    }

    public function toggle(BusTipoCombustible $combustible): JsonResponse
    {
        // Nota: Si en el futuro este modelo tiene relación con 'vehiculos', 
        // podrías validar aquí que no se inactive si está en uso, tal como hiciste en las marcas.

        $combustible->update(['estado' => $combustible->estado ? 0 : 1]);

        return response()->json([
            'success' => true,
            'message' => $combustible->estado ? 'Tipo de combustible activado.' : 'Tipo de combustible desactivado.',
            'data'    => $combustible->fresh(),
        ]);
    }

    public function destroy(BusTipoCombustible $combustible): JsonResponse
    {
        // Validación preventiva por si agregas la relación más adelante
        if (method_exists($combustible, 'vehiculos') && $combustible->vehiculos()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar: tiene vehículos asociados.',
            ], 422);
        }

        $combustible->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tipo de combustible eliminado.',
        ]);
    }
}