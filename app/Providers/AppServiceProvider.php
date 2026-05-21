<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Cargar migraciones que estén en subcarpetas de forma segura
        $mainPath = database_path('migrations');
        $directories = glob($mainPath . '/*', GLOB_ONLYDIR);
        $paths = array_merge([$mainPath], $directories);
        $this->loadMigrationsFrom($paths);

        // 2. Ejecutar la automigración SOLO si la petición viene desde la web
        // y NO desde la consola de comandos de Docker (composer install)
        if (!app()->runningInConsole()) {
            try {
                // Verificar la conexión de forma segura sin romper la app
                if (!Schema::hasTable('sessions')) {
                    // Forzamos la migración y los seeders inmediatamente al arrancar la app
                    Artisan::call('migrate', ['--force' => true]);
                    Artisan::call('db:seed', ['--force' => true]);
                }
            } catch (\Exception $e) {
                // Evitamos que muera el arranque si la BD tarda en responder
            }
        }
    }
}
