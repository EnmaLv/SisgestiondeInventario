<?php

namespace App\Http\Controllers\salud;

use App\Http\Controllers\Controller;
use App\Models\salud\Consultorio;
use App\Models\salud\HorarioConsultorio;
use Illuminate\Http\Request;

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

        return view('admin.salud.movimientos.horarios.create', compact(
            'consultorios',
            'consultorioSeleccionado',
            'ocupadosPorConsultorio'
        ));
    }

    /**
     * Guarda cada bloque seleccionado como un registro independiente.
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

        // Obtener todos los registros actuales de este consultorio (activos e inactivos)
        $existentes = HorarioConsultorio::where('consultorio_id', $consultorioId)->get();

        // Mapear registros existentes por la clave 'dia|hora_inicio|hora_fin'
        $existentesMap = $existentes->keyBy(function ($h) {
            $inicio = \Carbon\Carbon::parse($h->hora_inicio)->format('H:i');
            $fin    = \Carbon\Carbon::parse($h->hora_fin)->format('H:i');
            return "{$h->dia}|{$inicio}|{$fin}";
        });

        $clavesProcesadas = [];

        // Procesar los bloques seleccionados
        foreach ($seleccionados as $item) {
            $partes = explode('|', $item);

            if (count($partes) !== 3) {
                continue;
            }

            [$dia, $inicio, $fin] = $partes;

            if (!array_key_exists($dia, HorarioConsultorio::DIAS)) {
                continue;
            }

            $key = "{$dia}|{$inicio}|{$fin}";
            $clavesProcesadas[] = $key;

            if ($existentesMap->has($key)) {
                $horario = $existentesMap->get($key);
                // Si estaba inactivo, lo reactivamos
                if (!$horario->activo) {
                    $horario->update(['activo' => true]);
                }
            } else {
                // Si no existe, creamos el nuevo registro
                HorarioConsultorio::create([
                    'consultorio_id' => $consultorioId,
                    'dia'            => $dia,
                    'hora_inicio'    => $inicio,
                    'hora_fin'       => $fin,
                    'activo'         => true,
                ]);
            }
        }

        // Desactivar aquellos horarios que estaban activos pero el usuario desmarcó
        foreach ($existentes as $horario) {
            $inicio = \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i');
            $fin    = \Carbon\Carbon::parse($horario->hora_fin)->format('H:i');
            $key    = "{$horario->dia}|{$inicio}|{$fin}";

            if (!in_array($key, $clavesProcesadas) && $horario->activo) {
                $horario->update(['activo' => false]);
            }
        }

        return redirect()
            ->route('admin.salud.movimientos.horarios.index', ['consultorio_id' => $consultorioId])
            ->with('success', 'Horarios actualizados correctamente.');
    }


    public function destroy(HorarioConsultorio $horario)
    {
        $consultorioId = $horario->consultorio_id;
        $horario->update(['activo' => false]);

        return redirect()
            ->route('admin.salud.movimientos.horarios.index', ['consultorio_id' => $consultorioId])
            ->with('success', 'Bloque de horario eliminado.');
    }
}
