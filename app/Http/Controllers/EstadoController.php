<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use Illuminate\Http\Request;
use Livewire\WithPagination;

class EstadoController extends Controller
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    /**
     * Muestra todos los registros de estados.
     */
    public function index()
    {
        // Se obtienen todos los estados ordenados alfabéticamente por su nombre
        $estados = Estado::orderBy('nombre_estado', 'asc')->Paginate(5);

        // Se retorna la vista con los registros encontrados
        return view('admin.estado.index', compact('estados'));
    }

    /**
     * Guarda un nuevo estado en la base de datos.
     */
    public function store(Request $request)
    {
        // Se validan los datos enviados desde el formulario
        $validated = $request->validate([
            'nombre_estado' => 'required|string|max:255',
        ]);

        // Verificar si ya existe un estado activo con el mismo nombre
        $existe = Estado::where('nombre_estado', $validated['nombre_estado'])
            ->where('status', true)
            ->exists();

        if ($existe) {
            // Si existe un estado activo con el mismo nombre, se muestra un mensaje de error
            return redirect()
                ->route('admin.estado.index')
                ->with('error', 'Ya existe un estado activo con este nombre.');
        }

        try {
            // Se crea una nueva instancia del modelo Estado
            $estado = new Estado();
            $estado->nombre_estado = $validated['nombre_estado'];
            $estado->status = true; // Por defecto, el estado se guarda como activo
            $estado->save();

            // Mensaje de registro en la terminal
            info('Se ha creado un nuevo estado: ' . $estado->nombre_estado);

            // Se redirige con un mensaje de éxito visible al usuario
            return redirect()
                ->route('admin.estado.index')
                ->with('success', 'Estado creado exitosamente.');
        } catch (\Exception $e) {
            // Mensaje en la terminal en caso de error
            info('Error al crear un nuevo estado: ' . $e->getMessage());

            // Se redirige con un mensaje de error visible al usuario
            return redirect()
                ->route('admin.estado.index')
                ->with('error', 'Error al crear el estado: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza los datos de un estado existente.
     */
    public function update(Request $request, $id)
    {
        // Se busca el registro existente por su ID
        $estado = Estado::findOrFail($id);

        // Se validan los datos recibidos desde el formulario
        $validated = $request->validate([
            'nombre_estado' => 'required|string|max:255',
        ]);

        // Verificar si ya existe otro estado activo con el mismo nombre
        $existe = Estado::where('nombre_estado', $validated['nombre_estado'])
            ->where('status', true)
            ->where('id', '!=', $estado->id)
            ->exists();

        if ($existe) {
            // Si existe otro estado activo con el mismo nombre, se muestra un mensaje de error
            return redirect()
                ->route('admin.estado.index')
                ->with('error', 'Ya existe otro estado activo con este nombre.');
        }

        try {
            // Se actualiza el nombre del estado con el valor validado
            $estado->nombre_estado = $validated['nombre_estado'];
            $estado->save();

            // Mensaje en la terminal confirmando la actualización
            info('El estado con ID ' . $id . ' ha sido actualizado correctamente.');

            // Se redirige con mensaje de éxito
            return redirect()
                ->route('admin.estado.index')
                ->with('success', 'Estado actualizado exitosamente.');
        } catch (\Exception $e) {
            // Mensaje en la terminal si ocurre un error
            info('Error al actualizar el estado con ID ' . $id . ': ' . $e->getMessage());

            // Se redirige con mensaje de error
            return redirect()
                ->route('admin.estado.index')
                ->with('error', 'Error al actualizar el estado: ' . $e->getMessage());
        }
    }

    /**
     * Verifica si ya existe un estado con el nombre proporcionado.
     */
    public function verificarExistencia(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
            ]);

            $existe = Estado::where('nombre_estado', $request->nombre)
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
                'message' => 'Error al verificar el estado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Marca un estado como inactivo (eliminación lógica).
     */
    public function destroy($id)
    {
        // Se busca el estado por su ID
        $estado = Estado::find($id);

        // Si se encuentra el registro, se actualiza su estatus a inactivo
        if ($estado) {
            $estado->update(['status' => false]);

            // Mensaje en la terminal para seguimiento
            info('El estado "' . $estado->nombre_estado . '" ha sido marcado como inactivo.');

            // Se redirige con mensaje de éxito visible al usuario
            return redirect()
                ->route('admin.estado.index')
                ->with('success', 'Estado eliminado correctamente.');
        }

        // Si el registro no existe, se informa en la terminal
        info('No se encontró el estado con ID ' . $id . ' para eliminar.');

        // Y se redirige con mensaje de error
        return redirect()
            ->route('admin.estado.index')
            ->with('error', 'Estado no encontrado.');
    }
}
