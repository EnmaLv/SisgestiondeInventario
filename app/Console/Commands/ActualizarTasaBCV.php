<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExchangeRates;
use Illuminate\Support\Facades\Http;


class ActualizarTasaBCV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasa:actualizar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Obteniendo tasa desde DolarAPI...');

        $response = Http::get('https://ve.dolarapi.com/v1/dolares/oficial');

        if ($response->ok()) {
            $data = $response->json();

            // Guardamos o actualizamos la tasa en la base de datos
            ExchangeRates::updateOrCreate(
                ['nombre' => $data['nombre']], // 'Oficial'
                [
                    'fuente' => $data['fuente'], // 'oficial'
                    'promedio' => $data['promedio'],
                ]
            );

            $this->info("Tasa actualizada correctamente: {$data['promedio']} Bs/USD");
        } else {
            $this->error('Error al obtener la tasa desde la API.');
        }
    }
}
