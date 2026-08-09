<?php

namespace App\Http\Controllers\salud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Models\salud\Enfermedad;

class EnfermedadController extends Controller
{
    public function index(Request $request)
    {
        $tipo = session('modulo_activo', $request->get('tipo', 'fisica'));
        $returnTo = $request->get('return_to');
        $editing = $request->get('editing');
        $search = $request->get('search');

        $categoriaFiltro = $this->resolverCategoria($request);

        $enfermedades = Enfermedad::obtenerEnfermedades(10, $search, $categoriaFiltro);

        if ($request->ajax()) {
            return view('admin.enfermedades.components.disease_list', compact(
                'enfermedades', 'tipo', 'returnTo', 'search', 'categoriaFiltro', 'editing'
            ));
        }

        return view('admin.enfermedades.index', compact(
            'enfermedades', 'tipo', 'returnTo', 'search', 'categoriaFiltro', 'editing'
        ));
    }

    private function resolverCategoria(?Request $request = null): string
    {
        $moduloActivo = session('modulo_activo');

        if ($moduloActivo) {
            return match ($moduloActivo) {
                'psicologia', 'mental' => 'mental',
                'biopsicosocial'       => 'biopsicosocial',
                'medicina', 'salud'    => 'fisica',
                default                => 'fisica',
            };
        }

        $contexto = $request ? $request->get('tipo_contexto', $request->get('tipo')) : null;

        return match ($contexto) {
            'psicologia', 'mental' => 'mental',
            'biopsicosocial'       => 'biopsicosocial',
            default                => 'fisica',
        };
    }

    public function create(Request $request)
    {
        $categoria = $this->resolverCategoria($request);
        $tipo = session('modulo_activo', $request->get('tipo', 'fisica'));
        $returnTo = $request->get('return_to');
        $editing = $request->get('editing');

        return view('admin.enfermedades.create', compact('tipo', 'categoria', 'returnTo', 'editing'));
    }

    public function store(Request $request)
    {
        $categoria = $this->resolverCategoria($request);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:50',
            'nivel'  => 'nullable|integer|min:0|max:5',
        ]);

        $codigo = $validated['codigo'] ?? null;
        $nivel = $validated['nivel'] ?? 0;

        $existe = Enfermedad::existeEnfermedad($validated['nombre'], $codigo, $categoria);

        if ($existe) {
            return back()->withErrors(['nombre' => 'Esta enfermedad o código ya existe dentro de esta categoría.'])->withInput();
        }

        Enfermedad::crearEnfermedad([
            'nombre'    => $validated['nombre'],
            'codigo'    => $codigo,
            'categoria' => $categoria,
            'nivel'     => $nivel,
        ]);

        return redirect()->route('enfermedades.index', [
            'tipo'      => session('modulo_activo', $request->get('tipo', 'fisica')),
            'return_to' => $request->return_to,
            'editing'   => $request->editing
        ])->with('success', 'Enfermedad registrada correctamente.');
    }

    public function edit(Request $request, string $id)
    {
        $enfermedad = Enfermedad::obtenerPorId($id);

        if (!$enfermedad) {
            return redirect()->route('enfermedades.index')->with('error', 'Enfermedad no encontrada.');
        }

        $categoria = $this->resolverCategoria($request);
        $tipo = session('modulo_activo', $request->get('tipo', $enfermedad->categoria));
        $returnTo = $request->get('return_to');
        $editing = $request->get('editing');

        return view('admin.enfermedades.edit', compact('enfermedad', 'tipo', 'categoria', 'returnTo', 'editing'));
    }

    public function update(Request $request, string $id)
    {
        $categoria = $this->resolverCategoria($request);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:50',
            'nivel'  => 'nullable|integer|min:0|max:5',
        ]);

        $codigo = $validated['codigo'] ?? null;
        $nivel = $validated['nivel'] ?? 0;

        $existe = Enfermedad::existeEnfermedad($validated['nombre'], $codigo, $categoria, $id);

        if ($existe) {
            return back()->withErrors(['nombre' => 'Esta combinación de enfermedad y código ya existe.'])->withInput();
        }

        Enfermedad::actualizarEnfermedad($id, [
            'nombre'    => $validated['nombre'],
            'codigo'    => $codigo,
            'categoria' => $categoria,
            'nivel'     => $nivel,
        ]);

        return redirect()->route('enfermedades.index', [
            'tipo'      => session('modulo_activo', $request->get('tipo', 'fisica')),
            'return_to' => $request->return_to,
            'editing'   => $request->editing
        ])->with('success', 'Enfermedad actualizada correctamente.');
    }

    public function destroy(Request $request, $id)
    {
        Enfermedad::eliminarEnfermedad($id);

        return redirect()->route('enfermedades.index', [
            'tipo' => $request->tipo_contexto,
            'return_to' => $request->return_to,
            'editing' => $request->editing
        ])->with('success', 'Enfermedad eliminada correctamente.');
    }

    public function search(Request $request)
    {
        $search = $request->get('q');
        $categoria = $request->get('categoria');

        $enfermedades = Enfermedad::obtenerEnfermedades(20, $search, $categoria);

        return response()->json($enfermedades->items());
    }
}
