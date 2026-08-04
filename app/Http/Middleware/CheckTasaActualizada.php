<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ExchangeRates;
use Carbon\Carbon;

class CheckTasaActualizada
{
    public function handle($request, Closure $next)
    {
        // 🔹 Rutas libres (Agregamos 'tasa.ignorar')
        if (
            $request->routeIs('productos.actualizar.tasa') ||
            $request->routeIs('tasa.ignorar') ||
            $request->routeIs('logout')
        ) {
            return $next($request);
        }

        $hoy = Carbon::today()->toDateString();

        // 🔹 1. ¿Existe ALGUNA tasa?
        $existeAlgunaTasa = ExchangeRates::where('nombre', 'Oficial')->exists();

        if (!$existeAlgunaTasa) {
            session()->put('tasa_obligatoria', true);

            if (!$request->routeIs('home')) {
                return redirect()->route('home');
            }

            return $next($request);
        }

        // 🔹 2. ¿Existe tasa del día?
        $tasaHoy = ExchangeRates::whereDate('fecha_vigencia', $hoy)
            ->where('nombre', 'Oficial')
            ->first();

        // 🔹 3. ¿El usuario ya dijo "más tarde" hoy?
        $ignoradaHoy = session('tasa_ignorada_hasta') === $hoy;

        if (!$tasaHoy && !$ignoradaHoy) {
            session()->put('tasa_pendiente', true);
        } else {
            session()->forget('tasa_pendiente');
        }

        session()->forget('tasa_obligatoria');

        return $next($request);
    }
}
