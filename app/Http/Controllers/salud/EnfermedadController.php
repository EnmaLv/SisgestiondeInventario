<?php

namespace App\Http\Controllers\salud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\salud\Enfermedad;

class EnfermedadController extends Controller
{

    public function index(Request $request)
    {
        $categoriaFiltro = $this->resolverCategoria($request);
        $tipo = $request->get('tipo', $categoriaFiltro);
        $returnTo = $request->get('return_to');
        $editing = $request->get('editing');
        $search = $request->get('search');
        $activo = $request->get('activo', 1);

        $enfermedades = Enfermedad::obtenerEnfermedades(10, $search, $categoriaFiltro, $activo);

        $estilos = $this->obtenerConfiguracionEstilo($categoriaFiltro);

        $data = array_merge([
            'enfermedades'    => $enfermedades,
            'tipo'            => $tipo,
            'categoriaFiltro' => $categoriaFiltro,
            'returnTo'        => $returnTo,
            'editing'         => $editing,
            'search'          => $search,
            'activo'          => $activo,
        ], $estilos);

        return view('admin.enfermedades.index', $data);
    }

    private function resolverCategoria(?Request $request = null): string
    {
        $contexto = $request ? ($request->get('tipo') ?? $request->get('tipo_contexto')) : null;

        if ($contexto) {
            return match ($contexto) {
                'psicologia', 'mental'       => 'mental',
                'biopsicosocial'             => 'biopsicosocial',
                'medicina', 'salud', 'fisica' => 'fisica',
                default                      => 'fisica',
            };
        }

        $moduloActivo = session('modulo_activo');

        if ($moduloActivo) {
            return match ($moduloActivo) {
                'psicologia', 'mental'       => 'mental',
                'biopsicosocial'             => 'biopsicosocial',
                'medicina', 'salud', 'fisica' => 'fisica',
                default                      => 'fisica',
            };
        }

        return 'fisica';
    }

    private function obtenerConfiguracionEstilo(string $categoria): array
    {
        return match ($categoria) {
            'mental' => [
                'categoriaTexto' => 'Psicología / Salud Mental',
                'themeColor'     => 'indigo',
                'btnClass'       => 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-600/30 hover:shadow-indigo-600/40',
                'spinnerColor'   => 'border-indigo-600',
                'focusRingClass' => 'focus:ring-indigo-500',
            ],
            'biopsicosocial' => [
                'categoriaTexto' => 'Biopsicosocial',
                'themeColor'     => 'teal',
                'btnClass'       => 'bg-teal-600 hover:bg-teal-700 shadow-teal-600/30 hover:shadow-teal-600/40',
                'spinnerColor'   => 'border-teal-600',
                'focusRingClass' => 'focus:ring-teal-500',
            ],
            default => [
                'categoriaTexto' => 'Salud / Medicina General',
                'themeColor'     => 'sky',
                'btnClass'       => 'bg-sky-600 hover:bg-sky-700 shadow-sky-600/30 hover:shadow-sky-600/40',
                'spinnerColor'   => 'border-sky-600',
                'focusRingClass' => 'focus:ring-sky-500',
            ],
        };
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
            return back()
                ->withErrors(['nombre' => 'Esta enfermedad o código ya existe dentro de esta categoría.'])
                ->withInput()
                ->with('modal_mode', 'create');
        }

        $fromidreuse = Enfermedad::crearEnfermedad([
            'nombre'    => $validated['nombre'],
            'codigo'    => $codigo,
            'categoria' => $categoria,
            'nivel'     => $nivel,
        ]);


        $from = $request->input('from');

        if ($from) {
            return redirect($from . '?enfermedad_id=' . $fromidreuse)
                ->with('success', 'Enfermedad creada exitosamente.');
        } else {
            return redirect()->route('admin.enfermedades.index', array_filter([
                'tipo'      => $request->get('tipo_contexto', $request->get('tipo', $categoria)),
                'return_to' => $request->get('return_to'),
                'editing'   => $request->get('editing')
            ]))
            ->with('success', 'Enfermedad creada exitosamente.');
        }
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

        $nivel  = isset($validated['nivel']) && $validated['nivel'] !== '' ? (int)$validated['nivel'] : 0;

        $existe = Enfermedad::existeEnfermedad($validated['nombre'], $codigo, $categoria, $id);

        if ($existe) {
            return back()
                ->withErrors(['nombre' => 'Esta combinación de enfermedad y código ya existe.'])
                ->withInput()
                ->with('modal_mode', 'edit')
                ->with('editing_id', $id);
        }

        Enfermedad::actualizarEnfermedad($id, [
            'nombre'    => $validated['nombre'],
            'codigo'    => $codigo,
            'categoria' => $categoria,
            'nivel'     => $nivel,
        ]);

        return redirect()->route('admin.enfermedades.index', array_filter([
            'tipo'      => $request->get('tipo_contexto', $request->get('tipo', $categoria)),
            'return_to' => $request->get('return_to'),
            'editing'   => $request->get('editing')
        ]))->with('success', 'Enfermedad actualizada correctamente.');
    }

    public function destroy(Request $request, $id)
    {
        $categoria = $this->resolverCategoria($request);

        Enfermedad::eliminarEnfermedad($id);

        return redirect()->route('admin.enfermedades.index', array_filter([
            'tipo'      => $request->get('tipo_contexto', $request->get('tipo', $categoria)),
            'return_to' => $request->get('return_to'),
            'editing'   => $request->get('editing'),
            'activo'    => $request->get('activo', 1),
            'search'    => $request->get('search'),
        ]))->with('success', 'Enfermedad desactivada correctamente.');
    }

    public function activar(Request $request, $id)
    {
        $categoria = $this->resolverCategoria($request);

        Enfermedad::activar($id);

        return redirect()->route('admin.enfermedades.index', array_filter([
            'tipo'      => $request->get('tipo_contexto', $request->get('tipo', $categoria)),
            'return_to' => $request->get('return_to'),
            'editing'   => $request->get('editing'),
            'activo'    => $request->get('activo', 0),
            'search'    => $request->get('search'),
        ]))->with('success', 'Enfermedad activada correctamente.');
    }


    public function search(Request $request)
    {
        $search = $request->get('q');
        $categoria = $request->get('categoria', $this->resolverCategoria($request));

        $enfermedades = Enfermedad::obtenerEnfermedades(20, $search, $categoria);

        return response()->json($enfermedades->items());
    }
}
