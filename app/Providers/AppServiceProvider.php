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

    $this->loadMigrationsFrom($paths);

    }
}
