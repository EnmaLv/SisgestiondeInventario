<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
    //Cargar migraciones que esten en subcarpetasss
    // Cargamos la carpeta por defecto y las subcarpetas deseadas
        $mainPath = database_path('migrations');
        $directories = glob($mainPath . '/*', GLOB_ONLYDIR);
        $paths = array_merge([$mainPath], $directories);
        if (!Schema::hasTable('sessions')) {
            try {
                // Forzamos la migración y los seeders inmediatamente al arrancar la app
                Artisan::call('migrate', ['--force' => true]);
                Artisan::call('db:seed', ['--force' => true]);
            } catch (\Exception $e) {
                // Evitamos que muera el build si hay algún problema de red inicial
            }
        }

    $this->loadMigrationsFrom($paths);

    }
}
