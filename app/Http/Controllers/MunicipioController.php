<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use App\Models\Estado;
use Illuminate\Http\Request;

class MunicipioController extends Controller 
{
    /**
     * Muestra la lista de municipios junto con los estados disponibles.
     */
    public function index()
    {
        // Obtener todos los estados activos y ordenarlos alfabéticamente
        $estados = Estado::where('status', true)
            ->orderBy('nombre_estado', 'asc')
            ->get();

        // Obtener todos los municipios (activos e inactivos) ordenados por nombre
        $municipios = Municipio::orderBy('nombre_municipio', 'asc')->get();

        // Retornar la vista principal con los datos obtenidos
        return view('admin.municipio.index', compact('municipios', 'estados'));
    }

    /**
     * Muestra el modal para registrar un nuevo municipio.
     */
    public function createModal()
    {
        // Obtener los estados activos para mostrarlos en el formulario del modal
        $estados = Estado::where('status', true)
            ->orderBy('nombre_estado', 'asc')
            ->get();

        // Retornar la vista del modal con los estados disponibles
        return view('admin.municipio.modales.createModal', compact('estados'));
    }

    /**
     * Devuelve los municipios asociados a un estado específico.
     * Esta función se utiliza principalmente para los selects dependientes en el frontend.
     */
    public function getByEstado($estado_id)
    {
        // Buscar los municipios activos relacionados con el estado indicado
        $municipios = Municipio::where('estado_id', $estado_id)
            ->where('status', true)
            ->get(['id', 'nombre_municipio']);

        // Retornar los resultados en formato JSON
        return response()->json($municipios);
    }

    /**
     * Registra un nuevo municipio en la base de datos.
     */
    /**
     * Verifica si ya existe un municipio con el nombre proporcionado en el estado especificado.
     */
    public function verificarExistencia(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'estado_id' => 'required|exists:estados,id',
            ]);

            $existe = Municipio::where('nombre_municipio', $request->nombre)
                ->where('estado_id', $request->estado_id)
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
                'message' => 'Error al verificar el municipio',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        // Validar los datos ingresados por el usuario antes de guardar
        $validated = $request->validate([
            'nombre_municipio' => 'required|string|max:255',
            'estado_id' => 'required|exists:estados,id',
        ]);

        $existe = Municipio::where('nombre_municipio', $validated['nombre_municipio'])
            ->where('estado_id', $validated['estado_id'])
            ->where('status', true)
            ->exists();

        if ($existe) {
            return redirect()
                ->route('admin.municipio.index')
                ->with('error', 'Ya existe un municipio con el mismo nombre y estado.');
        }

        try {
            // Crear y asignar los valores al nuevo municipio
            $municipio = new Municipio();
            $municipio->nombre_municipio = $validated['nombre_municipio'];
            $municipio->estado_id = $validated['estado_id'];
            $municipio->status = true;
            $municipio->save();

            // Retornar a la vista con mensaje de éxito
            return redirect()
                ->route('admin.municipio.index')
                ->with('success', 'El municipio fue registrado correctamente.');
        } catch (\Exception $e) {
            // Retornar mensaje de error si ocurre alguna excepción
            return redirect()
                ->route('admin.municipio.index')
                ->with('error', 'Ocurrió un error al registrar el municipio: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza la información de un municipio existente.
     */
    public function update(Request $request, $id)
    {
        // Buscar el municipio que se desea modificar
        $municipio = Municipio::findOrFail($id);

        // Validar los datos enviados por el usuario
        $validated = $request->validate([
            'nombre_municipio' => 'required|string|max:255',
            'estado_id' => 'required|exists:estados,id',
        ]);

        $existe = Municipio::where('nombre_municipio', $validated['nombre_municipio'])
            ->where('estado_id', $validated['estado_id'])
            ->where('id', '!=', $municipio->id)
            ->where('status', true)
            ->exists();

        if ($existe) {
            return redirect()
                ->route('admin.municipio.index')
                ->with('error', 'Ya existe un municipio con el mismo nombre y estado.');
        }

        try {
            // Asignar los nuevos valores y guardar los cambios
            $municipio->nombre_municipio = $validated['nombre_municipio'];
            $municipio->estado_id = $validated['estado_id'];
            $municipio->save();

            // Retornar mensaje de éxito
            return redirect()
                ->route('admin.municipio.index')
                ->with('success', 'El municipio fue actualizado correctamente.');
        } catch (\Exception $e) {
            // Retornar mensaje de error si algo falla
            return redirect()
                ->route('admin.municipio.index')
                ->with('error', 'Ocurrió un error al actualizar el municipio: ' . $e->getMessage());
        }
    }

    /**
     * Desactiva un municipio (eliminación lógica del registro).
     */
    public function destroy($id)
    {
        // Buscar el municipio a eliminar
        $municipio = Municipio::find($id);

        if ($municipio) {
            // Cambiar el estado del municipio a inactivo
            $municipio->update([
                'status' => false,
            ]);

            // Retornar mensaje de confirmación al usuario
            return redirect()
                ->route('admin.municipio.index')
                ->with('success', 'El municipio fue eliminado correctamente.');
        }

        // Si no se encuentra el municipio, mostrar mensaje de error
        return redirect()
            ->route('admin.municipio.index')
            ->with('error', 'No se encontró el municipio especificado.');
    }
}
