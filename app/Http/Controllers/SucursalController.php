<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;

// Controlador para gestionar las operaciones de sucursales
class SucursalController extends Controller
{
    // Muestra el listado de sucursales con opciones de búsqueda y filtrado
    public function index(Request $request)
    {
        // Obtener sucursales con filtros aplicados
        $sucursales = Sucursal::listarSucursales(
            $request->input('buscar'),  // Término de búsqueda opcional
            $request->input('activo', 1)   // Filtro de estado opcional
        );

        // Mostrar la vista con las sucursales
        return view('admin.maestros.sucursales.index', compact('sucursales'));
    }

    // Muestra el formulario para crear una nueva sucursal
    public function create()
    {
        // Mostrar el formulario de creación
        return view('admin.maestros.sucursales.create');
    }

    // Almacena una nueva sucursal en la base de datos
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'nombre'    => 'required|string|max:255',    // Nombre obligatorio
            'direccion' => 'required|string|max:255',    // Dirección obligatoria
            'telefono'  => 'required|string|max:20',     // Teléfono obligatorio
            'activo'    => 'required|boolean',           // Estado activo/inactivo
        ]);

        // Crear la nueva sucursal
        Sucursal::crearSucursal($validated);

        // Redirigir al listado con mensaje de éxito
        return redirect()
            ->route('admin.maestros.sucursales.index')
            ->with('success', 'Sucursal creada exitosamente.')
            ->with('icono', 'success');
    }

    // Muestra los detalles de una sucursal específica
    public function show($id)
    {
        // Obtener la sucursal con su inventario
        $sucursal = Sucursal::obtenerSucursalConInventario($id);

        // Si no se encuentra la sucursal, redirigir con error
        if (!$sucursal) {
            return redirect()
                ->route('admin.maestros.sucursales.index')
                ->with('error', 'Sucursal no encontrada.')
                ->with('icono', 'error');
        }

        // Obtener estadísticas de la sucursal
        $estadisticas = Sucursal::obtenerEstadisticas($id);

        // Mostrar la vista de detalles con los datos
        return view('admin.maestros.sucursales.show', compact('sucursal', 'estadisticas'));
    }

    // Muestra el formulario para editar una sucursal existente
    public function edit($id)
    {
        // Obtener la sucursal por su ID
        $sucursal = Sucursal::obtenerSucursal($id);

        // Si no se encuentra la sucursal, redirigir con error
        if (!$sucursal) {
            return redirect()
                ->route('admin.maestros.sucursales.index')
                ->with('error', 'Sucursal no encontrada.')
                ->with('icono', 'error');
        }

        // Mostrar el formulario de edición con los datos de la sucursal
        return view('admin.maestros.sucursales.edit', compact('sucursal'));
    }

    // Actualiza los datos de una sucursal existente
    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'nombre'    => 'required|string|max:255',    // Nombre obligatorio
            'direccion' => 'required|string|max:255',    // Dirección obligatoria
            'telefono'  => 'required|string|max:20',     // Teléfono obligatorio
            'activo'    => 'required|boolean',           // Estado activo/inactivo
        ]);

        // Actualizar la sucursal con los nuevos datos
        Sucursal::actualizarSucursal($id, $validated);

        // Redirigir al listado con mensaje de éxito
        return redirect()
            ->route('admin.maestros.sucursales.index')
            ->with('success', 'Sucursal actualizada exitosamente.')
            ->with('icono', 'success');
    }

    // Elimina una sucursal del sistema
    public function destroy($id)
    {
        // Verificar si la sucursal tiene inventario
        if (Sucursal::tieneInventario($id)) {
            return redirect()
                ->route('admin.maestros.sucursales.index')
                ->with('error', 'No se puede eliminar la sucursal porque tiene inventario asociado.')
                ->with('icono', 'error');
        }

        // Verificar si la sucursal tiene movimientos de inventario
        if (Sucursal::tieneMovimientos($id)) {
            return redirect()
                ->route('admin.maestros.sucursales.index')
                ->with('error', 'No se puede eliminar la sucursal porque tiene movimientos de inventario.')
                ->with('icono', 'error');
        }

        // Si pasa las validaciones, proceder a eliminar
        Sucursal::eliminarSucursal($id);

        // Redirigir al listado con mensaje de éxito
        return redirect()
            ->route('admin.maestros.sucursales.index')
            ->with('success', 'Sucursal eliminada exitosamente.')
            ->with('icono', 'success');
    }

    public function activar($id)
    {
        Sucursal::activarSucursal($id);
        return redirect()->route('admin.maestros.sucursales.index')->with('success', 'Categoria activada exitosamente.');
    }

    // Exporta el listado de sucursales a un archivo CSV
    public function exportCsv(Request $request)
    {
        // Obtener las sucursales con los filtros aplicados
        $rows = Sucursal::exportarCSV(
            $request->input('buscar'),  // Término de búsqueda opcional
            $request->input('activo')   // Filtro de estado opcional
        );

        // Nombre del archivo con marca de tiempo
        $filename = 'sucursales_' . date('Ymd_His') . '.csv';

        // Configurar las cabeceras para la descarga
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        // Crear el contenido del CSV
        $callback = function () use ($rows) {
            $out = fopen('php://output', 'w');

            // Escribir encabezados
            fputcsv($out, ['ID', 'Nombre', 'Dirección', 'Teléfono', 'Estado']);

            // Escribir filas de datos
            foreach ($rows as $row) {
                fputcsv($out, [
                    $row->id,
                    $row->nombre,
                    $row->direccion,
                    $row->telefono,
                    $row->activo ? 'Activo' : 'Inactivo'  // Convertir a texto legible
                ]);
            }

            fclose($out);
        };

        // Devolver la respuesta de descarga
        return response()->stream($callback, 200, $headers);
    }
}
