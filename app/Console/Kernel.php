<?php

namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ActualizarTasaBCV;

class Kernel extends ConsoleKernel
{
    /**
     * Los comandos Artisan disponibles.
     *
     * @var array<int, class-string>
     */
    protected $commands = [
        ActualizarTasaBCV::class,
    ];

    /**
     * Define el programa de tareas.
     */
    protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule)
    {
        // Ejemplo: actualizar la tasa cada hora automáticamente
        // $schedule->command('tasa:actualizar')->hourly();
    }

    /**
     * Registra los comandos Artisan de la aplicación.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
