<?php 

namespace App\Http\Middleware;

use Closure;
use \App\Models\ExchangeRates;
use Carbon\Carbon;

class CheckTasaActualizada
{
    public function handle($request, Closure $next)
    {
        if (
            $request->routeIs('productos.actualizar.tasa') ||
            $request->routeIs('logout')
        ) {
            return $next($request);
        }

        $hoy = Carbon::today()->toDateString();
        
        $tasaHoy = ExchangeRates::whereDate('fecha_vigencia', $hoy)
            ->where('nombre', 'Oficial')
            ->first();

        if (!$tasaHoy) {
            session()->put('tasa_pendiente', true);

            if (!$request->routeIs('home')) {
                return redirect()->route('home');
            }
        } else {
            session()->forget('tasa_pendiente');
        }

        return $next($request);
    }
}