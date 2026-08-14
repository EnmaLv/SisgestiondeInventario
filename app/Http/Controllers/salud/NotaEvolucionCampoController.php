<?php

namespace App\Http\Controllers\salud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\salud\NotaEvolucionCampo;
use Illuminate\Support\Facades\Auth;

class NotaEvolucionCampoController extends Controller
{
    public function index()
    {
        $campos = NotaEvolucionCampo::obtenerCamposDisponiblesPaginados(Auth::id());
        return view('admin.psicologia.maestros.campos_evolucion.index', compact('campos'));
    }

    public function create()
    {
        return view('admin.psicologia.maestros.campos_evolucion.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
        ]);

        if (NotaEvolucionCampo::existeTitulo(Auth::id(), $data['titulo'])) {
            return redirect()->back()->withInput()->with('error', 'Ya existe un campo con ese nombre. Por favor, elige un título diferente.');
        }

        NotaEvolucionCampo::crearPersonalizado(Auth::id(), $data['titulo']);

        return redirect()->route('admin.psicologia.maestros.campos_evolucion.index')->with('success', 'Campo de evolución creado exitosamente.');
    }

    public function edit($id)
    {
        $campo = NotaEvolucionCampo::obtenerPorId($id, Auth::id());
        
        if (!$campo) {
            abort(404);
        }

        return view('admin.psicologia.maestros.campos_evolucion.edit', compact('campo'));
    }

    public function update(Request $request, $id)
    {
        $campo = NotaEvolucionCampo::obtenerPorId($id, Auth::id());
        
        if (!$campo) {
            abort(404);
        }

        $data = $request->validate([
            'titulo' => 'required|string|max:255',
        ]);

        if (NotaEvolucionCampo::existeTitulo(Auth::id(), $data['titulo'], $id)) {
            return redirect()->back()->withInput()->with('error', 'Ya existe otro campo con ese nombre. Por favor, elige un título diferente.');
        }

        NotaEvolucionCampo::actualizar($id, Auth::id(), $data['titulo']);

        return redirect()->route('admin.psicologia.maestros.campos_evolucion.index')->with('success', 'Campo de evolución actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $campo = NotaEvolucionCampo::obtenerPorId($id, Auth::id());
        
        if (!$campo) {
            abort(404);
        }

        NotaEvolucionCampo::eliminar($id, Auth::id());

        return redirect()->route('admin.psicologia.maestros.campos_evolucion.index')->with('success', 'Campo de evolución eliminado exitosamente.');
    }
}
