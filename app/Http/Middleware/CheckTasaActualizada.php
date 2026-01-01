<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use \App\Models\ExchangeRates;

class CheckTasaActualizada
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Rutas permitidas
        if (
            $request->routeIs('productos.actualizar.tasa') ||
            $request->routeIs('logout')
        ) {
            return $next($request);
        }

        $tasaVigente = ExchangeRates::whereDate('fecha_vigencia', '<=', now()->toDateString())
            ->orderByDesc('fecha_vigencia')
            ->first();

        if (!$tasaVigente) {
            session()->put('tasa_pendiente', true);

            if (!$request->routeIs('home')) {
                return redirect()->route('home');
            }
        }


        return $next($request);
    }
}
