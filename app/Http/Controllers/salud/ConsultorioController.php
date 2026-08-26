<?php

namespace App\Http\Controllers\salud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\salud\Consultorio;
use App\Models\Sede;
use Illuminate\Validation\Rule;

class ConsultorioController extends Controller
{
    public function index(Request $request)
    {
        $consultorios = Consultorio::listar(
            $request->input('buscar'),
            $request->input('activo', 1),
        );

        $sedes = Sede::where('activo', 1)->get();

        return view('admin.salud.maestros.consultorios.index', compact('consultorios', 'sedes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sede_id' => 'required|exists:sede,id',
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('consultorios', 'nombre')->where(function ($query) use ($request) {
                    return $query->where('sede_id', $request->input('sede_id'));
                }),
            ],
            'descripcion' => 'nullable|string|max:255',
        ], [
            'sede_id.required' => 'Debe seleccionar una sede.',
            'sede_id.exists'   => 'La sede seleccionada no es válida.',
            'nombre.required'  => 'El nombre del consultorio es obligatorio.',
            'nombre.unique'    => 'Ya existe un consultorio con este nombre en la sede seleccionada.',
        ]);

        Consultorio::crear($validated);

        return redirect()->to($request->input('from', route('admin.salud.maestros.consultorios.index')))
            ->with('success', 'Consultorio registrado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'sede_id' => 'required|exists:sede,id',
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('consultorios', 'nombre')
                    ->where('sede_id', $request->input('sede_id'))
                    ->ignore($id),
            ],
            'descripcion' => 'nullable|string|max:255',
        ], [
            'sede_id.required' => 'Debe seleccionar una sede.',
            'sede_id.exists'   => 'La sede seleccionada no es válida.',
            'nombre.required'  => 'El nombre del consultorio es obligatorio.',
            'nombre.unique'    => 'Ya existe un consultorio con este nombre en la sede seleccionada.',
        ]);

        Consultorio::actualizar($id, $validated);

        return redirect()->to($request->input('from', route('admin.salud.maestros.consultorios.index')))
            ->with('success', 'Consultorio actualizado correctamente.');
    }

    public function show($id)
    {
        $consultorio = Consultorio::with(['sede'])->findOrFail($id);

        return response()->json($consultorio);
    }

    public function destroy($id)
    {
        Consultorio::eliminar($id);

        return redirect()
            ->route('admin.salud.maestros.consultorios.index')
            ->with('success', 'Consultorio eliminado exitosamente.');
    }

    public function activar($id)
    {
        Consultorio::activar($id);
        return redirect()->route('admin.salud.maestros.consultorios.index')->with('success', 'Consultorio activado exitosamente.');
    }
}
