<?php

namespace App\Http\Controllers\salud;

use App\Http\Controllers\Controller;
use App\Models\salud\Consultorio;
use App\Models\Usuario;
use App\Models\salud\HorarioConsultorio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class HorarioConsultorioController extends Controller
{
    public function index(Request $request)
    {
        $consultorios = Consultorio::where('activo', true)->orderBy('nombre')->get();

        $consultorioId = (int) $request->input('consultorio_id', optional($consultorios->first())->id);
        $estadoFilter  = $request->input('estado', 'todos');

        $horarios = $consultorioId
            ? HorarioConsultorio::porConsultorioAgrupado($consultorioId)
            : collect();

        return view('admin.salud.movimientos.horarios.index', compact(
            'consultorios',
            'consultorioId',
            'horarios',
            'estadoFilter'
        ));
    }

    /**
     * Formulario de selección de bloques para un consultorio.
     */
    public function create(Request $request)
    {
        $consultorios = Consultorio::where('activo', true)->orderBy('nombre')->get();
        $consultorioSeleccionado = $request->input('consultorio_id');
        $ocupadosPorConsultorio = HorarioConsultorio::ocupadosPorConsultorio();

        // Horarios cargados previamente con persona Y roles del usuario
        $horariosActuales = $consultorioSeleccionado
            ? HorarioConsultorio::with(['usuario.persona', 'usuario.roles'])
            ->where('consultorio_id', $consultorioSeleccionado)
            ->where('activo', true)
            ->get()
            ->map(function ($h) {
                $inicio = \Carbon\Carbon::parse($h->hora_inicio)->format('H:i');
                $fin    = \Carbon\Carbon::parse($h->hora_fin)->format('H:i');

                // Nombre completo desde la tabla persona
                $nombreCompleto = trim(
                    ($h->usuario->persona->nombre_persona ?? '') . ' ' .
                        ($h->usuario->persona->apellido_persona ?? '')
                );

                // Rol del usuario
                $rolNombre = $h->usuario->roles->pluck('nombre')->first() ?? 'Sin Rol';

                return [
                    'clave_simple'   => "{$h->dia}|{$inicio}|{$fin}",
                    'id_usuario'     => $h->id_usuario,
                    'nombre_usuario' => $nombreCompleto ?: $h->usuario->username,
                    'rol_usuario'    => $rolNombre,
                ];
            })
            : collect();

        $slugsPermitidos = [
            'administrador',
            'administrador-de-salud',
            'secretaria-de-salud',
            'medicina'
        ];

        // Usuarios elegibles con persona Y roles
        $usuariosElegibles = Usuario::whereHas('roles', function ($q) use ($slugsPermitidos) {
            $q->whereIn('slug', $slugsPermitidos);
        })
            ->with(['persona', 'roles']) // Carga la relación 'persona'
            ->get();

        return view('admin.salud.movimientos.horarios.create', compact(
            'consultorios',
            'consultorioSeleccionado',
            'ocupadosPorConsultorio',
            'horariosActuales',
            'usuariosElegibles'
        ));
    }

    /**
     * Guarda cada asignación de usuario/bloque como un registro independiente.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'consultorio_id' => ['required', 'exists:consultorios,id'],
            'horarios'       => ['nullable', 'array'],
            'horarios.*'     => ['string'],
        ], [
            'consultorio_id.required' => 'Selecciona un consultorio.',
        ]);

        $consultorioId = $validated['consultorio_id'];
        $seleccionados = $request->input('horarios', []);

        // Obtener todos los registros existentes del consultorio
        $existentes = HorarioConsultorio::where('consultorio_id', $consultorioId)->get();

        // Mapear los existentes usando la clave completa incluyendo id_usuario
        $existentesMap = $existentes->keyBy(function ($h) {
            $inicio = \Carbon\Carbon::parse($h->hora_inicio)->format('H:i');
            $fin    = \Carbon\Carbon::parse($h->hora_fin)->format('H:i');
            return "{$h->dia}|{$inicio}|{$fin}|{$h->id_usuario}";
        });

        $clavesProcesadas = [];

        foreach ($seleccionados as $item) {
            // Formato esperado: "dia|hora_inicio|hora_fin|id_usuario"
            $partes = explode('|', $item);

            if (count($partes) < 4) {
                continue;
            }

            $dia       = $partes[0];
            $inicio    = $partes[1];
            $fin       = $partes[2];
            $idUsuario = !empty($partes[3]) ? (int)$partes[3] : null;

            if (!$idUsuario || !array_key_exists($dia, HorarioConsultorio::DIAS)) {
                continue;
            }

            // Clave única compuesta por bloque Y usuario
            $key = "{$dia}|{$inicio}|{$fin}|{$idUsuario}";
            $clavesProcesadas[] = $key;

            if ($existentesMap->has($key)) {
                $horario = $existentesMap->get($key);
                if (!$horario->activo) {
                    $horario->update(['activo' => true]);
                }
            } else {
                $nuevo = HorarioConsultorio::create([
                    'consultorio_id' => $consultorioId,
                    'id_usuario'     => $idUsuario,
                    'dia'            => $dia,
                    'hora_inicio'    => $inicio,
                    'hora_fin'       => $fin,
                    'activo'         => true,
                ]);
                $existentesMap->put($key, $nuevo);
            }
        }

        // Desactivar las asignaciones que fueron quitadas en el formulario
        foreach ($existentes as $horario) {
            $inicio = \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i');
            $fin    = \Carbon\Carbon::parse($horario->hora_fin)->format('H:i');
            $key    = "{$horario->dia}|{$inicio}|{$fin}|{$horario->id_usuario}";

            if (!in_array($key, $clavesProcesadas) && $horario->activo) {
                $horario->update(['activo' => false]);
            }
        }

        return redirect()
            ->route('admin.salud.movimientos.horarios.index', ['consultorio_id' => $consultorioId])
            ->with('success', 'Horarios asignados y actualizados correctamente.');
    }

    public function destroy(HorarioConsultorio $horario)
    {
        $consultorioId = $horario->consultorio_id;
        $horario->update(['activo' => false]);

        return redirect()
            ->route('admin.salud.movimientos.horarios.index', ['consultorio_id' => $consultorioId])
            ->with('success', 'Bloque de horario eliminado.');
    }

    public function exportarPdf(Request $request)
    {
        ini_set('memory_limit', '512M');

        $consultorios = Consultorio::where('activo', true)->orderBy('nombre')->get();
        $consultorioId = (int) $request->input('consultorio_id', optional($consultorios->first())->id);

        $consultorio = Consultorio::findOrFail($consultorioId);

        $horariosActivos = HorarioConsultorio::with('usuario.persona')
            ->where('consultorio_id', $consultorioId)
            ->where('activo', true)
            ->get();

        // Agrupar como array para soportar múltiples asignaciones por casilla
        $activosMap = [];
        foreach ($horariosActivos as $h) {
            $inicio = \Carbon\Carbon::parse($h->hora_inicio)->format('H:i');
            $fin    = \Carbon\Carbon::parse($h->hora_fin)->format('H:i');
            $activosMap["{$h->dia}|{$inicio}|{$fin}"][] = $h;
        }

        $bloquesJornadas = HorarioConsultorio::BLOQUES;
        $dias = HorarioConsultorio::DIAS;

        $pdf = Pdf::loadView('admin.salud.movimientos.horarios.pdf', compact(
            'consultorio',
            'activosMap',
            'bloquesJornadas',
            'dias'
        ))->setPaper('a4', 'landscape');

        return $pdf->stream('Horario_Consultorio_' . Str::slug($consultorio->nombre) . '.pdf');
    }
}
