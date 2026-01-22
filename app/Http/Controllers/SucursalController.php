<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SucursalController extends Controller
{
    public function index(Request $request)
    {
        $sucursales = Sucursal::listarSucursales(
            $request->input('buscar'),
            $request->input('activo', 1)
        );
        return view('admin.maestros.sucursales.index', compact('sucursales'));
    }

    public function create()
    {
        return view('admin.maestros.sucursales.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                'unique:sucursals,nombre'
            ],
            'direccion' => 'required|string|max:255',
            'telefono'  => 'required|string|max:20',
        ], [
            'nombre.unique' => 'Ya existe una sede con este nombre',
        ]);

        Sucursal::crearSucursal($validated);
        return redirect()
            ->route('admin.maestros.sucursales.index')
            ->with('success', 'Sucursal creada exitosamente.')
            ->with('icono', 'success');
    }

    public function show($id)
    {
        $sucursal = Sucursal::obtenerSucursalConInventario($id);

        if (!$sucursal) {
            return redirect()
                ->route('admin.maestros.sucursales.index')
                ->with('error', 'Sucursal no encontrada.')
                ->with('icono', 'error');
        }

        $estadisticas = Sucursal::obtenerEstadisticas($id);
        return view('admin.maestros.sucursales.show', compact('sucursal', 'estadisticas'));
    }

    public function edit($id)
    {
        $sucursal = Sucursal::obtenerSucursal($id);
        if (!$sucursal) {
            return redirect()
                ->route('admin.maestros.sucursales.index')
                ->with('error', 'Sucursal no encontrada.')
                ->with('icono', 'error');
        }
        return view('admin.maestros.sucursales.edit', compact('sucursal'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sucursals', 'nombre')->ignore($id),
            ],
            'direccion' => 'required|string|max:255',
            'telefono'  => 'required|string|max:20',
        ], [
            'nombre.unique' => 'Ya existe una sede con este nombre',
        ]);

        Sucursal::actualizarSucursal($id, $validated);

        return redirect()
            ->route('admin.maestros.sucursales.index')
            ->with('success', 'Sucursal actualizada exitosamente.')
            ->with('icono', 'success');
    }

    public function destroy($id)
    {
        if (Sucursal::tieneInventario($id)) {
            return redirect()
                ->route('admin.maestros.sucursales.index')
                ->with('error', 'No se puede eliminar la sucursal porque tiene inventario asociado.')
                ->with('icono', 'error');
        }

        if (Sucursal::tieneMovimientos($id)) {
            return redirect()
                ->route('admin.maestros.sucursales.index')
                ->with('error', 'No se puede eliminar la sucursal porque tiene movimientos de inventario.')
                ->with('icono', 'error');
        }

        Sucursal::eliminarSucursal($id);

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

    public function exportCsv(Request $request)
    {
        $rows = Sucursal::exportarCSV(
            $request->input('buscar'),
            $request->input('activo')
        );

        $filename = 'sucursales_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Nombre', 'Dirección', 'Teléfono', 'Estado']);
            foreach ($rows as $row) {
                fputcsv($out, [
                    $row->id,
                    $row->nombre,
                    $row->direccion,
                    $row->telefono,
                    $row->activo ? 'Activo' : 'Inactivo'
                ]);
            }

            fclose($out);
        };
        return response()->stream($callback, 200, $headers);
    }
}
