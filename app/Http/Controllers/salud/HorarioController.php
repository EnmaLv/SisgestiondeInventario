<?php

namespace App\Http\Controllers\salud;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\salud\Horario;
use App\Models\salud\GrupoHorario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HorarioController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $dias = Horario::diasSemana();
        $filtroDia = $request->get('dia');
        $grupoId = $request->get('grupo');

        $grupoActivo = GrupoHorario::obtenerActivoPorPsicologo($userId);

        $grupoSeleccionado = null;
        if ($grupoId) {
            $grupoSeleccionado = GrupoHorario::obtenerPorIdYUsuario($grupoId, $userId);

            if ($grupoActivo && $grupoSeleccionado && $grupoActivo->id === $grupoSeleccionado->id) {
                $request->session()->reflash();
                return redirect()->route('admin.psicologia.maestros.horarios.index', $filtroDia ? ['dia' => $filtroDia] : []);
            }
        }

        $currentGrupoId = $grupoSeleccionado ? $grupoSeleccionado->id : ($grupoActivo ? $grupoActivo->id : null);
        $horarios = Horario::obtenerPorFiltros($userId, $currentGrupoId, $filtroDia);

        $horariosPorDia = [];
        foreach ($dias as $dia) {
            $horariosPorDia[$dia] = $horarios->where('dia', $dia);
        }

        $tieneCitasPendientes = Horario::hasPendingCitas($userId);

        return view('admin.psicologia.maestros.horarios.index', compact('horarios', 'horariosPorDia', 'dias', 'filtroDia', 'grupoActivo', 'tieneCitasPendientes', 'grupoSeleccionado'));
    }

    public function create(Request $request)
    {
        $dias = Horario::diasSemana();
        $tieneCitasPendientes = false;
        $grupoRetorno = $request->query('grupo');

        return view('admin.psicologia.maestros.horarios.create', compact('dias', 'tieneCitasPendientes', 'grupoRetorno'));
    }

    public function edit(Request $request, $id)
    {
        $horario = Horario::obtenerPorId($id);
        abort_if(!$horario || $horario->user_id !== Auth::id(), 403);

        if (Horario::hasPendingCitas(Auth::id())) {
            return redirect()->route('admin.psicologia.maestros.horarios.index')->with('error', 'No puedes modificar bloques de horario mientras tengas citas pendientes o confirmadas.');
        }

        $dias = Horario::diasSemana();
        $grupoRetorno = $request->query('grupo', $horario->grupo_horario_id);

        return view('admin.psicologia.maestros.horarios.edit', compact('horario', 'dias', 'grupoRetorno'));
    }

    public function store(Request $request)
    {
        $horaInicio = $this->parseTimeInput(
            $request->input('hora_inicio_hora'),
            $request->input('hora_inicio_minuto'),
            $request->input('hora_inicio_periodo')
        );
        $horaFin = $this->parseTimeInput(
            $request->input('hora_fin_hora'),
            $request->input('hora_fin_minuto'),
            $request->input('hora_fin_periodo')
        );

        $request->merge([
            'hora_inicio' => $horaInicio,
            'hora_fin' => $horaFin,
        ]);

        $grupoEspecificoId = $request->input('grupo_id');

        if ($grupoEspecificoId) {
            $grupoAModificar = GrupoHorario::obtenerPorIdYUsuario($grupoEspecificoId, Auth::id());

            $activoPorDefecto = ($grupoAModificar && $grupoAModificar->activo == GrupoHorario::STATUS_ACTIVE) ? Horario::STATUS_ACTIVE : Horario::STATUS_INACTIVE;
            $grupoAsignarId = $grupoAModificar ? $grupoAModificar->id : null;
        } else {
            $grupoActivo = GrupoHorario::obtenerActivoPorPsicologo(Auth::id());

            $activoPorDefecto = $grupoActivo ? Horario::STATUS_ACTIVE : Horario::STATUS_INACTIVE;
            $grupoAsignarId = $grupoActivo ? $grupoActivo->id : null;
        }

        $validated = $request->validate([
            'dia' => 'required|string|in:' . implode(',', Horario::diasSemana()),
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'descripcion' => 'nullable|string',
        ]);

        if (Horario::overlaps(Auth::id(), $validated['dia'], $validated['hora_inicio'], $validated['hora_fin'], null, $grupoAsignarId)) {
            return back()
                ->withErrors(['hora_inicio' => 'El bloque de horario se superpone con otro existente.'])
                ->withInput();
        }

        Horario::crear([
            'user_id' => Auth::id(),
            'dia' => $validated['dia'],
            'hora_inicio' => $validated['hora_inicio'],
            'hora_fin' => $validated['hora_fin'],
            'descripcion' => $validated['descripcion'],
            'activo' => $activoPorDefecto,
            'grupo_horario_id' => $grupoAsignarId,
        ]);

        return redirect()
            ->route('admin.psicologia.maestros.horarios.index', $grupoAsignarId ? ['grupo' => $grupoAsignarId] : [])
            ->with('success', 'Bloque de tiempo creado correctamente.');
    }

    public function show(Request $request, $id)
    {
        $horario = Horario::obtenerPorId($id);
        abort_if(!$horario || $horario->user_id !== Auth::id(), 403);
        $grupoRetorno = $request->query('grupo', $horario->grupo_horario_id);

        return view('admin.psicologia.maestros.horarios.show', compact('horario', 'grupoRetorno'));
    }

    public function update(Request $request, $id)
    {
        $horario = Horario::obtenerPorId($id);
        abort_if(!$horario || $horario->user_id !== Auth::id(), 403);

        if (Horario::hasPendingCitas(Auth::id())) {
            return redirect()->route('admin.psicologia.maestros.horarios.index')->with('error', 'No puedes modificar bloques de horario mientras tengas citas pendientes o confirmadas.');
        }

        $horaInicio = $this->parseTimeInput(
            $request->input('hora_inicio_hora'),
            $request->input('hora_inicio_minuto'),
            $request->input('hora_inicio_periodo')
        );
        $horaFin = $this->parseTimeInput(
            $request->input('hora_fin_hora'),
            $request->input('hora_fin_minuto'),
            $request->input('hora_fin_periodo')
        );

        $request->merge([
            'hora_inicio' => $horaInicio,
            'hora_fin' => $horaFin,
        ]);

        $validated = $request->validate([
            'dia' => 'required|string|in:' . implode(',', Horario::diasSemana()),
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'descripcion' => 'nullable|string',
        ]);

        if (Horario::overlaps(Auth::id(), $validated['dia'], $validated['hora_inicio'], $validated['hora_fin'], $horario->id, $horario->grupo_horario_id)) {
            return back()
                ->withErrors(['hora_inicio' => 'El bloque de horario se superpone con otro existente.'])
                ->withInput();
        }

        Horario::actualizar($horario->id, [
            'dia' => $validated['dia'],
            'hora_inicio' => $validated['hora_inicio'],
            'hora_fin' => $validated['hora_fin'],
            'descripcion' => $validated['descripcion'],
        ]);

        return redirect()
            ->route('admin.psicologia.maestros.horarios.index', $horario->grupo_horario_id ? ['grupo' => $horario->grupo_horario_id] : [])
            ->with('success', 'Bloque de tiempo actualizado correctamente.');
    }

    public function destroy(Request $request, $id)
    {
        $horario = Horario::obtenerPorId($id);
        abort_if(!$horario || $horario->user_id !== Auth::id(), 403);

        if (Horario::hasPendingCitas(Auth::id())) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No puedes modificar bloques de horario mientras tengas citas pendientes o confirmadas.'
                ], 400);
            }
            return redirect()->route('admin.psicologia.maestros.horarios.index')->with('error', 'No puedes modificar bloques de horario mientras tengas citas pendientes o confirmadas.');
        }

        $grupoId = $horario->grupo_horario_id;
        if ($grupoId) {
            $count = DB::table('horarios')
                ->where('grupo_horario_id', $grupoId)
                ->where('activo', '!=', Horario::STATUS_DELETED)
                ->count();
            if ($count <= 2) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'No puedes eliminar este bloque porque el grupo de horarios debe tener al menos dos bloques.'
                    ], 400);
                }
                return redirect()->route('admin.psicologia.maestros.horarios.index', ['grupo' => $grupoId])
                    ->with('error', 'No puedes eliminar este bloque porque el grupo de horarios debe tener al menos dos bloques.');
            }
        }

        Horario::eliminar($horario->id);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Bloque de horario eliminado correctamente.',
                'grupo_id' => $grupoId
            ], 200);
        }

        return redirect()
            ->route('admin.psicologia.maestros.horarios.index', $grupoId ? ['grupo' => $grupoId] : [])
            ->with('success', 'Bloque de horario eliminado correctamente.');
    }

    public function activate($id)
    {
        $horario = Horario::obtenerPorId($id);
        abort_if(!$horario || $horario->user_id !== Auth::id(), 403);

        if (Horario::hasPendingCitas(Auth::id())) {
            return redirect()->route('admin.psicologia.maestros.horarios.index')->with('error', 'No puedes modificar bloques de horario mientras tengas citas pendientes o confirmadas.');
        }

        Horario::actualizar($horario->id, ['activo' => Horario::STATUS_ACTIVE]);

        return redirect()->route('admin.psicologia.maestros.horarios.index')->with('success', 'Bloque de tiempo activado.');
    }

    public function deactivate($id)
    {
        $horario = Horario::obtenerPorId($id);
        abort_if(!$horario || $horario->user_id !== Auth::id(), 403);

        if (Horario::hasPendingCitas(Auth::id())) {
            return redirect()->route('admin.psicologia.maestros.horarios.index')->with('error', 'No puedes modificar bloques de horario mientras tengas citas pendientes o confirmadas.');
        }

        Horario::actualizar($horario->id, ['activo' => Horario::STATUS_INACTIVE]);

        return redirect()->route('admin.psicologia.maestros.horarios.index')->with('success', 'Bloque de tiempo desactivado.');
    }

    private function parseTimeInput($hora, $minuto, $periodo)
    {
        if (empty($hora) || empty($minuto) || empty($periodo)) {
            return null;
        }

        $hora = (int)$hora;
        if ($periodo === 'PM' && $hora < 12) {
            $hora += 12;
        } elseif ($periodo === 'AM' && $hora === 12) {
            $hora = 0;
        }

        return str_pad($hora, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minuto, 2, '0', STR_PAD_LEFT);
    }

    public function exportarPdf(Request $request)
    {
        ini_set('memory_limit', '512M');
        $userId = Auth::id();
        $dias = Horario::diasSemana();
        $grupoId = $request->get('grupo');

        $grupoActivo = GrupoHorario::obtenerActivoPorPsicologo($userId);
        $grupoSeleccionado = null;
        if ($grupoId) {
            $grupoSeleccionado = GrupoHorario::obtenerPorIdYUsuario($grupoId, $userId);
        }
        $currentGrupoId = $grupoSeleccionado ? $grupoSeleccionado->id : ($grupoActivo ? $grupoActivo->id : null);

        $horarios = Horario::obtenerPorFiltros($userId, $currentGrupoId, null);

        $horariosPorDia = [];
        foreach ($dias as $dia) {
            $horariosPorDia[$dia] = $horarios->where('dia', $dia);
        }

        $psicologo = \App\Models\Usuario::obtenerUsuarioPorId($userId);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.psicologia.maestros.horarios.pdf', compact('horariosPorDia', 'dias', 'psicologo'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Horario_Psicologo_' . \Illuminate\Support\Str::slug($psicologo->name) . '.pdf');
    }
}
