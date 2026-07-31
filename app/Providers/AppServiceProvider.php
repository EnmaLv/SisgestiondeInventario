<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
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
        Blade::if('canMenu', function ($keys) {
            return auth()->check() && auth()->user()->canAccessMenu($keys);
        });

        Blade::if('canModule', function ($moduleKey) {
            if (!auth()->check()) {
                return false;
            }

            $moduloActivo = session('modulo_activo');
            $permitidos = session('modulos_permitidos', []);

            // Si no hay módulo activo, si no coincide con el solicitado, o no está permitido para el usuario
            if (!$moduloActivo || $moduloActivo !== $moduleKey || !in_array($moduleKey, $permitidos)) {
                return false;
            }

            return true;
        });

        $mainPath = database_path('migrations');
        $directories = glob($mainPath . '/*', GLOB_ONLYDIR);
        $paths = array_merge([$mainPath], $directories);
        $this->loadMigrationsFrom($paths);

        if (!app()->runningInConsole()) {
            try {
                if (!Schema::hasTable('sessions')) {
                    Artisan::call('migrate', ['--force' => true]);
                    Artisan::call('db:seed', ['--force' => true]);
                }
            } catch (\Exception $e) {
            }
        }
    }
}
