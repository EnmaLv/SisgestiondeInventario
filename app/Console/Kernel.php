<?php

namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;


class Kernel extends ConsoleKernel
{
    /**
     * Los comandos Artisan disponibles.
     *
     * @var array<int, class-string>
     */


    /**
     * Define el programa de tareas.
     */
    protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule)
    {
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
