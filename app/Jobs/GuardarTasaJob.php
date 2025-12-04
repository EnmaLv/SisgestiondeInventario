<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\ExchangeRates;
use App\Models\Producto;
use App\Models\PrecioProducto;

class GuardarTasaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1️⃣ Verificar si ya hay una tasa guardada
        $tasa = ExchangeRates::latest()->first();

        // 2️⃣ Si no hay, obtener desde la API
        if (!$tasa || !$tasa->promedio) {
            $response = Http::get('https://ve.dolarapi.com/v1/dolares/oficial');

            if ($response->ok()) {
                $data = $response->json();

                $tasa = ExchangeRates::updateOrCreate(
                    ['nombre' => $data['nombre'] ?? 'Oficial'],
                    [
                        'fuente' => $data['fuente'] ?? 'BCV',
                        'promedio' => $data['promedio'] ?? 0,
                    ]
                );
            } else {
                // Si la API falla, ponemos un valor por defecto para que no rompa
                $tasa = ExchangeRates::firstOrCreate(
                    ['nombre' => 'Oficial'],
                    ['fuente' => 'BCV', 'promedio' => 1]
                );
            }
        }

        // 3️⃣ Recalcular precio_compra en Bs para todos los productos
        $precioProductos = PrecioProducto::with('producto')->get();

        foreach ($precioProductos as $pp) {
            $producto = $pp->producto;

            if ($producto) {
                // Determinar precio en USD: usar precio_usd si existe, si no costo_usd
                $precioUSD = $pp->precio_usd ?? $pp->costo_usd;
                $margen = $pp->margen ?? 0;

                // Aplicar margen y convertir a bolívares
                $precioBs = round($precioUSD * (1 + $margen / 100) * $tasa->promedio, 2);

                // Guardar en precio_compra del producto
                $producto->precio_compra = $precioBs;
                $producto->save();
            }
        }
    }
}
