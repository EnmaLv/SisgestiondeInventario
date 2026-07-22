<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusViaje;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\BusGpsLog;

class BusViajeApiController extends Controller
{

    public function registrarGps(Request $request, BusViaje $viaje): JsonResponse
    {
        if ($viaje->conductor_id !== $request->user()->id_usuario) {
            return response()->json(['success' => false, 'message' => 'No autorizado.'], 403);
        }

        $validated = $request->validate([
            'lat'       => 'required|numeric',
            'lng'       => 'required|numeric',
            'velocidad' => 'nullable|numeric',
            'heading'   => 'nullable|numeric',
        ]);

        // Guardar el log GPS
        $viaje->gpsLogs()->create([
            'lat'           => $validated['lat'],
            'lng'           => $validated['lng'],
            'velocidad'     => $validated['velocidad'] ?? 0,
            'heading'       => $validated['heading'] ?? 0,
            'registrado_en' => now(),
            'origen'        => 'app_flutter',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Coordenadas registradas correctamente.',
        ]);
    }

    public function obtenerPosicion(BusViaje $viaje): JsonResponse
    {
        $ultimoLog = $viaje->gpsLogs()->latest('id')->first();

        return response()->json([
            'success'         => true,
            'latitud'         => $ultimoLog ? (float) $ultimoLog->lat : null,
            'longitud'        => $ultimoLog ? (float) $ultimoLog->lng : null,
            'velocidad'       => $ultimoLog ? (float) $ultimoLog->velocidad : 0,
            'pasajeros'       => $viaje->pasajeros,
            'distancia_km'    => $viaje->distancia_km,
            'litros_gastados' => $viaje->litros_gastados,
            'estado'          => $viaje->estado,
            'actualizado_hace' => $ultimoLog ? $ultimoLog->created_at->diffForHumans() : 'Sin registros',
        ]);
    }

    public function miViajeActivo(Request $request): JsonResponse
    {
        $usuario = $request->user();

        $viaje = BusViaje::delConductor($usuario->id_usuario)
            ->whereIn('estado', ['programado', 'en_curso'])
            ->with([
                'vehiculo.tipoCombustible',
                'busRuta.paradas' => fn($q) => $q->orderBy('orden', 'asc')
            ])
            ->first();

        if (!$viaje) {
            return response()->json([
                'success' => true,
                'message' => 'No tienes ningún viaje activo o programado en este momento.',
                'data'    => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'data'    => $viaje,
        ]);
    }

    public function iniciar(Request $request, BusViaje $viaje): JsonResponse
    {
        if ($viaje->conductor_id !== $request->user()->id_usuario) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para iniciar este viaje.',
            ], 403);
        }

        if ($viaje->estado !== 'programado') {
            return response()->json([
                'success' => false,
                'message' => "No se puede iniciar el viaje porque su estado es '{$viaje->estado}'.",
            ], 422);
        }

        $kmInicio = $viaje->vehiculo->km_actual ?? 0;

        $viaje->update([
            'estado'       => 'en_curso',
            'fecha_inicio' => Carbon::now(),
            'km_inicio'    => $kmInicio,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'El viaje ha iniciado correctamente.',
            'data'    => $viaje->fresh(['vehiculo', 'busRuta.paradas']),
        ]);
    }

    public function finalizar(Request $request, BusViaje $viaje): JsonResponse
    {
        if ($viaje->conductor_id !== $request->user()->id_usuario) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para finalizar este viaje.',
            ], 403);
        }

        if ($viaje->estado !== 'en_curso') {
            return response()->json([
                'success' => false,
                'message' => 'Solo se pueden finalizar viajes que estén en curso.',
            ], 422);
        }

        $validated = $request->validate([
            'km_fin'          => 'required|numeric|gte:' . $viaje->km_inicio,
            'pasajeros'       => 'required|integer|min:0',
            'litros_gastados' => 'nullable|numeric|min:0',
            'hubo_desvio'     => 'nullable|boolean',
            'motivo_desvio'   => 'nullable|required_if:hubo_desvio,true|string|max:255',
        ], [
            'km_fin.gte'      => 'El kilometraje final no puede ser menor al kilometraje de inicio (' . $viaje->km_inicio . ' km).',
            'motivo_desvio.required_if' => 'Debe indicar el motivo del desvío.',
        ]);

        $kmFin = $validated['km_fin'];
        $distanciaRecorrida = $kmFin - $viaje->km_inicio;

        $viaje->update([
            'estado'          => 'finalizado',
            'km_fin'          => $kmFin,
            'distancia_km'    => $distanciaRecorrida,
            'pasajeros'       => $validated['pasajeros'],
            'litros_gastados' => $validated['litros_gastados'] ?? 0,
            'hubo_desvio'     => $validated['hubo_desvio'] ?? false,
            'motivo_desvio'   => $validated['motivo_desvio'] ?? null,
        ]);

        if ($viaje->vehiculo) {
            $viaje->vehiculo->update([
                'km_actual' => $kmFin,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Viaje finalizado exitosamente.',
            'data'    => $viaje->fresh(),
        ]);
    }

    public function historial(Request $request): JsonResponse
    {
        $viajes = BusViaje::delConductor($request->user()->id_usuario)
            ->where('estado', 'finalizado')
            ->with(['vehiculo', 'busRuta'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $viajes,
        ]);
    }
}
