<?php

namespace App\Models\salud;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Models\Usuario;
use Carbon\Carbon;
use Exception;

class HistoriaClinica extends Model
{
    protected $table = 'historia_clinicas';

    protected $fillable = [
        'user_id',
        'psicologo_id'
    ];

    public function paciente()
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'id_usuario');
    }

    public function psicologo()
    {
        return $this->belongsTo(Usuario::class, 'psicologo_id', 'id_usuario');
    }

    public function secciones()
    {
        return $this->hasMany(SeccionPersonalizada::class, 'historia_clinica_id');
    }

    public static function obtenerPaciente($userId)
    {
        return Usuario::where('id_usuario', $userId)->first();
    }

    public static function obtenerPsicologo($psicologoId)
    {
        return Usuario::where('id_usuario', $psicologoId)->first();
    }

    public static function obtenerSeccionesPersonalizadas($historiaId)
    {
        return SeccionPersonalizada::where('historia_clinica_id', $historiaId)
            ->orderBy('orden')
            ->get();
    }

    public static function obtenerPorPaciente($pacienteId)
    {
        return self::where('user_id', $pacienteId)->first();
    }

    public static function iniciarHistoria($pacienteId, $psicologoId)
    {
        try {
            return DB::transaction(function () use ($pacienteId, $psicologoId) {
                $historia = self::firstOrCreate(
                    ['user_id' => $pacienteId],
                    ['psicologo_id' => $psicologoId]
                );

                $seccionesActivas = SeccionPersonalizada::where('historia_clinica_id', $historia->id)
                    ->where('status', 1)
                    ->count();

                if ($seccionesActivas === 0) {
                    $plantillaGlobal = DB::table('historia_plantillas_globales')
                        ->where('psicologo_id', $psicologoId)
                        ->orderBy('updated_at', 'desc')
                        ->first();

                    if ($plantillaGlobal) {
                        $secciones = json_decode($plantillaGlobal->secciones, true) ?? [];
                        $maxOrden = 0;

                        foreach ($secciones as $seccionData) {
                            $maxOrden++;
                            $seccion = SeccionPersonalizada::create([
                                'historia_clinica_id' => $historia->id,
                                'titulo' => $seccionData['titulo'],
                                'descripcion_general' => $seccionData['descripcion_general'] ?? null,
                                'orden' => $maxOrden,
                                'status' => 1,
                            ]);

                            $segmentos = $seccionData['segmentos'] ?? [];
                            foreach ($segmentos as $indexSeg => $segmentoTitulo) {
                                if (!empty(trim($segmentoTitulo))) {
                                    SegmentoPersonalizado::create([
                                        'seccion_id' => $seccion->id,
                                        'titulo' => $segmentoTitulo,
                                        'contenido' => null,
                                        'orden' => $indexSeg + 1,
                                    ]);
                                }
                            }
                        }
                    }
                }

                return $historia;
            });
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function buscarPacientes($query, $psicologoId)
    {
        return Usuario::whereHas('citas', function ($q) use ($psicologoId) {
            $q->where('psicologo_id', $psicologoId);
        })
        ->where(function ($q) use ($query) {
            $q->where('nombres', 'like', '%' . $query . '%')
              ->orWhere('apellidos', 'like', '%' . $query . '%')
              ->orWhere('cedula', 'like', '%' . $query . '%');
        })
        ->get(['id_usuario as id', 'nombres', 'apellidos', 'email'])
        ->map(function ($usuario) {
            return (object) [
                'id' => $usuario->id,
                'name' => trim(($usuario->nombres ?? '') . ' ' . ($usuario->apellidos ?? '')),
                'email' => $usuario->email
            ];
        });
    }

    public static function obtenerListado($psicologoId, $search = null, $filters = [])
    {
        $query = DB::table('citas')
            ->join('usuario', 'citas.user_id', '=', 'usuario.id_usuario')
            ->leftJoin('historia_clinicas', 'usuario.id_usuario', '=', 'historia_clinicas.user_id')
            ->where('citas.psicologo_id', $psicologoId)
            ->where('citas.estado', 'realizada');

        if (!empty($search)) {
            $query->leftJoin('historia_enfermedad', 'historia_clinicas.id', '=', 'historia_enfermedad.historia_clinica_id')
                  ->leftJoin('enfermedades', 'historia_enfermedad.enfermedad_id', '=', 'enfermedades.id')
                  ->where(function ($q) use ($search) {
                      $q->where('usuario.nombres', 'like', "%{$search}%")
                        ->orWhere('usuario.apellidos', 'like', "%{$search}%")
                        ->orWhere('usuario.cedula', 'like', "%{$search}%");
                  });
        }

        if (!empty($filters['pnf'])) {
            $query->where('usuario.pnf', $filters['pnf']);
        }

        if (!empty($filters['edad'])) {
            $query->whereYear('usuario.fecha_nacimiento', Carbon::now()->subYears($filters['edad'])->year);
        }

        $tipoFiltroFecha = $filters['tipo_filtro_fecha'] ?? 'rango';
        $fechaDesde = $filters['fecha_desde'] ?? null;
        $fechaHasta = $filters['fecha_hasta'] ?? null;

        if (!empty($fechaDesde) || !empty($fechaHasta)) {
            if ($tipoFiltroFecha === 'primera_cita') {
                $subquery = DB::table('citas as sub_citas')
                    ->select('sub_citas.user_id')
                    ->where('sub_citas.psicologo_id', $psicologoId)
                    ->where('sub_citas.estado', 'realizada')
                    ->groupBy('sub_citas.user_id');

                if (!empty($fechaDesde)) {
                    $subquery->havingRaw('MIN(sub_citas.fecha) >= ?', [$fechaDesde]);
                }
                if (!empty($fechaHasta)) {
                    $subquery->havingRaw('MIN(sub_citas.fecha) <= ?', [$fechaHasta]);
                }

                $query->whereIn('usuario.id_usuario', $subquery->pluck('user_id'));
            } elseif ($tipoFiltroFecha === 'ultima_cita') {
                $subquery = DB::table('citas as sub_citas')
                    ->select('sub_citas.user_id')
                    ->where('sub_citas.psicologo_id', $psicologoId)
                    ->where('sub_citas.estado', 'realizada')
                    ->groupBy('sub_citas.user_id');

                if (!empty($fechaDesde)) {
                    $subquery->havingRaw('MAX(sub_citas.fecha) >= ?', [$fechaDesde]);
                }
                if (!empty($fechaHasta)) {
                    $subquery->havingRaw('MAX(sub_citas.fecha) <= ?', [$fechaHasta]);
                }

                $query->whereIn('usuario.id_usuario', $subquery->pluck('user_id'));
            } else {
                if (!empty($fechaDesde)) {
                    $query->whereDate('citas.fecha', '>=', $fechaDesde);
                }
                if (!empty($fechaHasta)) {
                    $query->whereDate('citas.fecha', '<=', $fechaHasta);
                }
            }
        }

        if (!empty($filters['prioridad'])) {
            $query->where('citas.prioridad', $filters['prioridad']);
        }

        if (!empty($filters['estado_animo_id'])) {
            $query->where('citas.estado_animo_id', $filters['estado_animo_id']);
        }

        if (!empty($filters['enfermedad_id'])) {
            $query->leftJoin('historia_enfermedad as he_filter', 'historia_clinicas.id', '=', 'he_filter.historia_clinica_id')
                  ->where('he_filter.enfermedad_id', $filters['enfermedad_id'])
                  ->where('he_filter.status', 1);
        }

        $historiasBase = $query->select(
                'usuario.id_usuario as id',
                DB::raw("CONCAT(usuario.nombres, ' ', usuario.apellidos) as patient_name"),
                'usuario.email',
                'citas.fecha as ultima_sesion',
                'citas.notas'
            )
            ->orderBy('citas.fecha', 'desc')
            ->get()
            ->unique('id');

        if (!empty($filters['avance_id'])) {
            $historiasBase = $historiasBase->filter(function ($item) use ($filters) {
                if (empty($item->notas)) return false;
                try {
                    $notasDecrypted = Crypt::decryptString($item->notas);
                    $notasArr = json_decode($notasDecrypted, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($notasArr)) {
                        return isset($notasArr['avance_estado']) && $notasArr['avance_estado'] == $filters['avance_id'];
                    }
                } catch (Exception $e) {}
                return false;
            });
        }

        return $historiasBase->map(function ($item) use ($psicologoId) {
            $h = self::obtenerPorPaciente($item->id);
            $citasRealizadas = DB::table('citas')
                ->where('user_id', $item->id)
                ->where('psicologo_id', $psicologoId)
                ->where('estado', 'realizada')
                ->where('status', 1)
                ->get(['id', 'motivo']);

            $countCitas = $citasRealizadas->filter(function ($cita) {
                try {
                    return Crypt::decryptString($cita->motivo) !== 'Nota de Evolución (Manual)';
                } catch (Exception $e) {
                    return true;
                }
            })->count();

            $paciente = self::obtenerPaciente($item->id);
            if ($paciente) {
                $paciente->name = trim(($paciente->nombres ?? '') . ' ' . ($paciente->apellidos ?? ''));
                $paciente->avatar = strtoupper(
                    substr($paciente->nombres ?? '', 0, 1) . substr($paciente->apellidos ?? '', 0, 1)
                );
            }

            $diagnosticoText = 'Sin diagnóstico';
            if ($h) {
                $diagSegment = SeccionPersonalizada::where('historia_clinica_id', $h->id)
                    ->where('titulo', 'Diagnóstico')
                    ->whereHas('segmentos', function ($q) {
                        $q->where('titulo', 'Diagnóstico Inicial (Resumen)');
                    })
                    ->first();

                if ($diagSegment) {
                    $segmento = SegmentoPersonalizado::where('seccion_id', $diagSegment->id)
                        ->where('titulo', 'Diagnóstico Inicial (Resumen)')
                        ->first();

                    if ($segmento && !empty($segmento->contenido)) {
                        try {
                            $diagnosticoText = Crypt::decryptString($segmento->contenido);
                        } catch (Exception $e) {
                        }
                    }
                }
            }

            return [
                'id'              => $item->id,
                'paciente_name'   => $item->patient_name,
                'email'           => $item->email,
                'ultima_sesion'   => $item->ultima_sesion,
                'notas'           => $item->notas,
                'citas_realizadas'=> $countCitas,
                'diagnostico'     => $diagnosticoText,
                'paciente'        => $paciente,
            ];
        });
    }

    public static function vincularEnfermedad($historiaId, $enfermedadId, $contexto)
    {
        try {
            return DB::transaction(function () use ($historiaId, $enfermedadId, $contexto) {
                $existe = null;
                if (str_starts_with($contexto, 'seg_')) {
                    $segmentoId = str_replace('seg_', '', $contexto);
                    $segmento = SegmentoPersonalizado::find($segmentoId);
                    if ($segmento) {
                        $segmentosSeccion = SegmentoPersonalizado::where('seccion_id', $segmento->seccion_id)
                            ->pluck('id')
                            ->map(fn($id) => 'seg_' . $id)
                            ->toArray();

                        $existe = DB::table('historia_enfermedad')
                            ->where('historia_clinica_id', $historiaId)
                            ->where('enfermedad_id', $enfermedadId)
                            ->whereIn('contexto', $segmentosSeccion)
                            ->first();
                    }
                } else {
                    $existe = DB::table('historia_enfermedad')
                        ->where('historia_clinica_id', $historiaId)
                        ->where('enfermedad_id', $enfermedadId)
                        ->where('contexto', $contexto)
                        ->first();
                }

                if (!$existe) {
                    $id = DB::table('historia_enfermedad')->insertGetId([
                        'historia_clinica_id' => $historiaId,
                        'enfermedad_id' => $enfermedadId,
                        'contexto' => $contexto,
                        'created_at' => now(),
                    ]);
                    
                    $enfermedad = Enfermedad::find($enfermedadId);
                    return [
                        'success' => true,
                        'link_id' => $id,
                        'nombre' => $enfermedad ? $enfermedad->nombre : '',
                        'contexto' => $contexto
                    ];
                }
                
                return ['success' => false, 'message' => 'Ya está vinculada'];
            });
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al vincular: ' . $e->getMessage()];
        }
    }

    public static function desvincularEnfermedad($linkId)
    {        
        try {
            DB::table('historia_enfermedad')
                ->where('id', $linkId)
                ->update([
                    'status' => 0,
                    'updated_at' => now()
                ]);

            return [true, 'Enfermedad desvinculada correctamente.'];
        } catch (Exception $e) {
            return [false, 'Error al desvincular: ' . $e->getMessage()];
        }
    }

    public static function obtenerEnfermedadesVinculadas($historiaId)
    {
        return DB::table('historia_enfermedad')
            ->join('enfermedades', 'historia_enfermedad.enfermedad_id', '=', 'enfermedades.id')
            ->where('historia_enfermedad.historia_clinica_id', $historiaId)
            ->where('historia_enfermedad.status', 1)
            ->select('enfermedades.*', 'historia_enfermedad.id as link_id', 'historia_enfermedad.contexto')
            ->get()
            ->groupBy('contexto');
    }

    public static function verificarAcceso($pacienteId, $psicologoId)
    {
        return self::where('user_id', $pacienteId)
            ->where('psicologo_id', $psicologoId)
            ->first();
    }

    public static function obtenerPorId($id)
    {
        return self::find($id);
    }

    public static function obtenerPorPacienteOrFail($pacienteId)
    {
        $historia = self::obtenerPorPaciente($pacienteId);

        if (!$historia) {
            abort(404);
        }

        return $historia;
    }

    public static function obtenerSeccionesConSegmentos($historiaId)
    {
        $secciones = SeccionPersonalizada::where('historia_clinica_id', $historiaId)
            ->where('status', 1)
            ->orderBy('orden')
            ->get();

        foreach ($secciones as $seccion) {
            $seccion->segmentos = SegmentoPersonalizado::where('seccion_id', $seccion->id)
                ->get()
                ->map(function ($segmento) {
                    if (!empty($segmento->contenido)) {
                        try {
                            $segmento->contenido = Crypt::decryptString($segmento->contenido);
                        } catch (Exception $e) {
                        }
                    }
                    return $segmento;
                });
        }

        return $secciones;
    }
}