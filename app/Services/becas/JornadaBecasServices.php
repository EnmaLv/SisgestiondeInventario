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
        $jornadasExpiradas = JornadaBeca::where('activa', 1)
            ->whereDate('fecha_fin_solicitud', '<', $hoy);


        // Si encuentra al menos una jornada que ya pasó su lapso...
        if ($jornadasExpiradas->exists()) {
            $jornadasExpiradas->update(['activa' => 0]);
            $this->limpiarCacheJornada();
        }

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
            
            
            //Revisamos si en el nombre de la jornada puso el lapso(2026-I, 2026-II, etc)
            $lapsoElegido = Lapso::find($validated['lapsos_id'])->codigo;
            //Verificamos si el string el usuario introdujo el lapso para identificar la jornada
            if(!str_contains($validated['nombre_jornada'], $lapsoElegido)){
                //Insertamos el lapso al string
                $validated['nombre_jornada'] .= ' ' . $lapsoElegido;
            }
            
            //Inicializamos los cupos asignados
            $validated['cupos_asignados'] = 0;
            
            //1.Validacion: Validar que el beneficio seleccionado, tenga los cupos necesario para hacer la jornada
            $beneficio = Beneficio::findOrFail($validated['beneficio_id']);
            if ($beneficio->cupones_disponibles < $validated['cupos_maximos']) {
                throw new \Exception('El número de cupos máximos excede los cupos disponibles del beneficio.');
            }
            //Guardamos la Jornada como paso final
            $jornada = JornadaBeca::create($validated);
            
            // IMPORTANTE: Al crear una nueva, invalidamos la caché anterior
            $this->limpiarCacheJornada();

            DB::commit();

            return $jornada;
        } catch (\Exception $e) {
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
}