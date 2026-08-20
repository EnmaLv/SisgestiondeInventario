<?php
namespace App\Services\becas;

use App\Models\Becas\JornadaBeca;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Becas\Lapso;
use App\Models\Becas\Beneficio;
use Exception;

class JornadaBecasServices
{
    /**
     * Clave única para almacenar la jornada activa en la caché del servidor.
     */
    protected const CACHE_KEY_ACTIVA = 'jornada_beca_activa';

    /**
     * Obtiene la jornada activa optimizada con caché.
     * Ideal para el home o dashboard del estudiante.
     */
    public function obtenerJornadaActiva()
    {
        $hoy = now()->toDateString();

        // Buscamos si hay jornadas marcadas como activas pero que ya vencieron en fecha
        $this->desactivarJornadasExpiradas();
        

        // Si ya está en la memoria RAM (caché), la devuelve. Si no, va a la BD una sola vez.
        return Cache::remember(self::CACHE_KEY_ACTIVA, now()->addHours(12), function () use ($hoy) {
            return JornadaBeca::where('activa', 1)
                ->whereDate('fecha_inicio_solicitud', '<=', $hoy)
                ->whereDate('fecha_fin_solicitud', '>=', $hoy)
                ->get();
        });
    }

    /**
     * Crea una nueva jornada de becas y limpia la caché para asegurar frescura de datos.
     * @param array $validated Los datos de la jornada
     * @return JornadaBeca Devuelve los datos de la jornada creada
     */
    public function crearJornada(array $validated): JornadaBeca
    {
        // Usamos una transacción por seguridad
        DB::beginTransaction();
        try {
            // Normalizar nombre de la jornada agregando el lapso si no está presente
            $validated['nombre_jornada'] = $this->normalizarNombreConLapso($validated['nombre_jornada'], $validated['lapsos_id']);
            
            // Inicializamos los cupos asignados
            $validated['cupos_assigned'] = 0; // Nota: en BD el campo es cupos_asignados
            $validated['cupos_asignados'] = 0;
            
            // Validar cupos disponibles
            $this->validarCuposDisponibles($validated['beneficio_id'], $validated['cupos_maximos']);

            // Guardamos la Jornada como paso final
            $jornada = JornadaBeca::create($validated);
            
            // IMPORTANTE: Al crear una nueva, invalidamos la caché anterior
            $this->limpiarCacheJornada();

            DB::commit();

            return $jornada;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Fuerza la desactivación manual de una jornada y limpia la caché.
     */
    public function desactivarJornada(int $id): JornadaBeca
    {
        $jornada = JornadaBeca::findOrFail($id);
        $jornada->update(['activa' => 0]);

        $this->limpiarCacheJornada();

        return $jornada;
    }

    /**
     * Fuerza la activación manual de una jornada y limpia la caché.
     */
    public function activarJornada(int $id): JornadaBeca
    {
        $jornada = JornadaBeca::findOrFail($id);
        $jornada->update(['activa' => 1]);

        $this->limpiarCacheJornada();

        return $jornada;
    }

    /**
     * Actualiza una jornada de becas existente y limpia la caché.
     */
    public function actualizarJornada(int $id, array $datos): JornadaBeca
    {
        return DB::transaction(function () use ($id, $datos) {
            $jornada = JornadaBeca::findOrFail($id);

            // 1. Si la jornada ya expiró, no se permite ninguna modificación
            if ($jornada->fecha_fin_solicitud && $jornada->fecha_fin_solicitud->isPast()) {
                throw new Exception('No se puede actualizar una jornada que ya ha expirado.');
            }

            // 2. Si la jornada ya inició, no se permite modificar campos críticos
            if ($jornada->fecha_inicio_solicitud && $jornada->fecha_inicio_solicitud->isPast()) {
                $inicioFormateadoOriginal = $jornada->fecha_inicio_solicitud->format('Y-m-d');
                $nuevoInicio = isset($datos['fecha_inicio_solicitud']) ? date('Y-m-d', strtotime($datos['fecha_inicio_solicitud'])) : '';
                
                if ($jornada->beneficio_id != $datos['beneficio_id'] || 
                    $jornada->lapsos_id != $datos['lapsos_id'] || 
                    $inicioFormateadoOriginal !== $nuevoInicio ||
                    $jornada->activa != ($datos['activa'] ?? 0)) {
                    throw new Exception('No se pueden modificar campos críticos (beneficio, lapso, fecha de inicio o estado activa/inactiva) en una jornada que ya ha iniciado.');
                }
            }

            // Validar cupos disponibles
            $this->validarCuposDisponibles($datos['beneficio_id'], $datos['cupos_maximos']);

            // Normalizar nombre de la jornada agregando el lapso si no está presente
            $datos['nombre_jornada'] = $this->normalizarNombreConLapso($datos['nombre_jornada'], $datos['lapsos_id']);

            $jornada->update($datos);

            $this->limpiarCacheJornada();

            return $jornada;
        });
    }

    /**
     * Obtiene todas las jornadas de becas para la pagina index de admin con filtros.
     */
    public function index()
    {
        //Desactivamos jornadas expiradas
        $this->desactivarJornadasExpiradas();

        $buscar = request('buscar');

        $query = JornadaBeca::with('beneficio');

        //Verificamos que la request no este vacia
        if (!empty($buscar)) {
            $query->where('nombre_jornada', 'like', '%' . $buscar . '%');
        }

        $jornadas = $query->paginate(10)->withQueryString();

        return view('admin.becas.jornada.index', compact('jornadas'));
    }

    /**
     * Helper interno para limpiar la caché cuando ocurren cambios.
     */
    public function limpiarCacheJornada(): void
    {
        Cache::forget(self::CACHE_KEY_ACTIVA);
    }

    /**
     * Valida que el beneficio tenga cupos disponibles suficientes para la jornada.
     * @throws Exception
     */
    private function validarCuposDisponibles(int $beneficioId, int $cuposMaximos): void
    {
        $beneficio = Beneficio::findOrFail($beneficioId);
        if ($beneficio->cupones_disponibles < $cuposMaximos) {
            throw new Exception('El número de cupos máximos excede los cupos disponibles del beneficio.');
        }
    }

    /**
     * Asegura que el nombre de la jornada contenga el código del lapso académico.
     */
    private function normalizarNombreConLapso(string $nombreJornada, int $lapsoId): string
    {
        $lapsoElegido = Lapso::findOrFail($lapsoId)->codigo;
        if (!str_contains($nombreJornada, $lapsoElegido)) {
            $nombreJornada .= ' ' . $lapsoElegido;
        }
        return $nombreJornada;
    }

    /**
     * Helpper para desactivar jornadas expiradas automaticamente 
     */
    private function desactivarJornadasExpiradas()
    {
        //Tomamos el dia de hoy
        $hoy = now()->toDateString();

        //Buscamos las jornadas que esten activas
        $jornadasExpiradas = JornadaBeca::where('activa', 1)
            ->whereDate('fecha_fin_solicitud', '<', $hoy);
        
        //Si hay jornadas expiradas
        if ($jornadasExpiradas->exists()) {
            //Las desactivamos
            $jornadasExpiradas->update(['activa' => 0]);
            //Limpiamos la cache
            $this->limpiarCacheJornada();
        }
    }
}