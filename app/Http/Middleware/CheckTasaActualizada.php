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

        // ¿Existe tasa hoy?
        $tasaHoy = ExchangeRates::whereDate('created_at', now()->toDateString())
            ->exists();

        if (!$tasaHoy) {

            session()->put('tasa_pendiente', true);

            if (!$request->routeIs('home')) {
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
