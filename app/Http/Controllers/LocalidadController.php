<?php

namespace App\Http\Controllers;

use App\Models\Localidad;
use App\Models\Municipio;
use App\Models\Estado;
use Illuminate\Http\Request;

class LocalidadController extends Controller
{
    /**
     * Muestra la lista de localidades existentes junto con sus municipios y estados.
     */
    public function index()
    {
        // Obtener todos los municipios activos, ordenados alfabéticamente
        $municipios = Municipio::where('status', true)
            ->orderBy('nombre_municipio', 'asc')
            ->get();

        // Obtener todos los estados activos, ordenados alfabéticamente
        $estados = Estado::where('status', true)
            ->orderBy('nombre_estado', 'asc')
            ->get();

        // Obtener todas las localidades junto con sus relaciones de estado y municipio
        $localidades = Localidad::with(['estado', 'municipio'])
            ->orderBy('nombre_localidad', 'asc')
            ->get();

        // Retornar la vista principal con los datos recopilados
        return view('admin.localidad.index', compact('localidades', 'municipios', 'estados'));
    }

    /**
     * Muestra el modal para crear una nueva localidad.
     */
    public function createModal()
    {
        // Obtener los municipios activos ordenados alfabéticamente
        $municipios = Municipio::where('status', true)
            ->orderBy('nombre_municipio', 'asc')
            ->get();

        // Obtener los estados activos ordenados alfabéticamente
        $estados = Estado::where('status', true)
            ->orderBy('nombre_estado', 'asc')
            ->get();

        // Retornar la vista del modal con los datos necesarios
        return view('admin.localidad.modales.createModal', compact('municipios', 'estados'));
    }

    /**
     * Devuelve las localidades asociadas a un municipio específico.
     * Se utiliza para cargar datos dinámicamente en los formularios.
     */
    public function getByMunicipio($municipio_id)
    {
        // Buscar las localidades activas pertenecientes al municipio indicado
        $localidades = Localidad::where('municipio_id', $municipio_id)
            ->where('status', true)
            ->get(['id', 'nombre_localidad']);

        // Retornar los resultados en formato JSON
        return response()->json($localidades);
    }


    /**
     * Verifica si ya existe una localidad con el nombre proporcionado en el municipio especificado.
     */
    public function verificarExistencia(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'municipio_id' => 'required|exists:municipios,id',
            ]);

            $existe = Localidad::where('nombre_localidad', $request->nombre)
                ->where('municipio_id', $request->municipio_id)
                ->where('status', true)
                ->exists();

            return response()->json([
                'success' => true,
                'existe' => $existe
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en verificarExistencia: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar la localidad',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Registra una nueva localidad en la base de datos.
     */
    public function store(Request $request)
    {
        // Validar los datos ingresados por el usuario
        $validated = $request->validate([
            'nombre_localidad' => 'required|string|max:255',
            'municipio_id' => 'required|exists:municipios,id',
        ]);

        $existe = Localidad::where('nombre_localidad', $validated['nombre_localidad'])
            ->where('municipio_id', $validated['municipio_id'])
            ->where('status', true)
            ->exists();

        if ($existe) {
            return redirect()
                ->route('admin.localidad.index')
                ->with('error', 'Ya existe una localidad con el mismo nombre y municipio.');
        }

        try {
            // Crear una nueva instancia de localidad y asignar los valores validados
            $localidad = new Localidad();
            $localidad->nombre_localidad = $validated['nombre_localidad'];
            $localidad->municipio_id = $validated['municipio_id'];
            $localidad->status = true;
            $localidad->save();

            // Retornar con mensaje de éxito
            return redirect()
                ->route('admin.localidad.index')
                ->with('success', 'La localidad fue registrada correctamente.');
        } catch (\Exception $e) {
            // Retornar mensaje de error en caso de fallo
            return redirect()
                ->route('admin.localidad.index')
                ->with('error', 'Ocurrió un error al registrar la localidad: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza la información de una localidad existente.
     */
    public function update(Request $request, $id)
    {
        // Buscar la localidad a actualizar
        $localidad = Localidad::findOrFail($id);

        // Validar los nuevos datos proporcionados por el usuario
        $validated = $request->validate([
            'nombre_localidad' => 'required|string|max:255',
            'municipio_id' => 'required|exists:municipios,id',
        ]);

        $existe = Localidad::where('nombre_localidad', $validated['nombre_localidad'])
            ->where('municipio_id', $validated['municipio_id'])
            ->where('id', '!=', $localidad->id)
            ->where('status', true)
            ->exists();

        if ($existe) {
            return redirect()
                ->route('admin.localidad.index')
                ->with('error', 'Ya existe una localidad con el mismo nombre y municipio.');
        }

        try {
            // Actualizar los valores de la localidad
            $localidad->nombre_localidad = $validated['nombre_localidad'];
            $localidad->municipio_id = $validated['municipio_id'];
            $localidad->status = true;
            $localidad->save();

            // Retornar mensaje de éxito
            return redirect()
                ->route('admin.localidad.index')
                ->with('success', 'La localidad fue actualizada correctamente.');
        } catch (\Exception $e) {
            // Retornar mensaje de error si algo falla
            return redirect()
                ->route('admin.localidad.index')
                ->with('error', 'Ocurrió un error al actualizar la localidad: ' . $e->getMessage());
        }
    }

    /**
     * Desactiva una localidad (eliminación lógica del registro).
     */
    public function destroy($id)
    {
        // Buscar la localidad que se desea desactivar
        $localidad = Localidad::find($id);

        if ($localidad) {
            // Cambiar su estado a inactiva
            $localidad->update(['status' => false]);

            // Retornar mensaje de confirmación
            return redirect()
                ->route('admin.localidad.index')
                ->with('success', 'La localidad fue eliminada correctamente.');
        }

        // Retornar mensaje si no se encuentra la localidad
        return redirect()
            ->route('admin.localidad.index')
            ->with('error', 'No se encontró la localidad especificada.');
    }
}
