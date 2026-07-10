<?php

namespace App\Http\Controllers;

use App\Http\Requests\BecaRequest;
use App\Http\Resources\BecaResource;
use App\Models\Becas\Beca;
use App\Services\BecaFormService;
use App\Services\BecaService;
use Illuminate\Http\Request;

class BecaController extends Controller
{
    public function __construct(
        private BecaService $becaService,
        private BecaFormService $formService,
    ) {
    }

    public function index(Request $request)
    {
        $becas = $this->becaService->listar($request->only(['buscar', 'activo']));

        return view('admin.becas.index', compact('becas'));
    }

    public function create()
    {
        return view('admin.becas.create', $this->formService->datosFormulario());
    }

    public function store(BecaRequest $request)
    {
        $beca = $this->becaService->crear($request->validated());

        return redirect()
            ->route('admin.becas.index')
            ->with('success', 'Beca creada exitosamente con codigo ' . $beca->codigo . '.');
    }

    public function show(Beca $beca)
    {
        $beca->load(['beneficios', 'tutores.tutor', 'tutores.rol']);

        return view('admin.becas.show', compact('beca'));
    }

    public function edit(Beca $beca)
    {
        $beca->load(['beneficios', 'asignacionesTrabajo.tutor', 'beneficiarios.persona', 'beneficiarios.tutor', 'tutores.tutor.usuarios.roles']);

        return view('admin.becas.edit', $this->formService->datosFormulario() + compact('beca'));
    }

    public function update(BecaRequest $request, Beca $beca)
    {
        $resultado = $this->becaService->actualizar($beca, $request->validated());

        return redirect()
            ->route('admin.becas.edit', $resultado['beca'])
            ->with('success', 'Beca actualizada exitosamente.')
            ->with('beneficios_alerta', $resultado['beneficios_cambiaron']);
    }

    public function toggle(Beca $beca)
    {
        $beca = $this->becaService->cambiarEstado($beca);
        $mensaje = $beca->activo ? 'Beca activada exitosamente.' : 'Beca desactivada exitosamente.';

        return back()->with('success', $mensaje);
    }

    public function json(Beca $beca)
    {
        return new BecaResource($beca->load(['beneficios', 'asignacionesTrabajo.tutor']));
    }
}
