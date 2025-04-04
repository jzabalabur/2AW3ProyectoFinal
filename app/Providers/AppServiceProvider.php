<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\LanguageMiddleware;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->make(\Illuminate\Contracts\Http\Kernel::class)
            ->pushMiddleware(LanguageMiddleware::class);
                // Forzar la carga de traducciones personalizadas
    $this->loadTranslationsFrom(resource_path('lang/vendor/adminlte'), 'adminlte');
    
    // Configuración especial para AdminLTE
    $this->app->singleton('adminlte_lang', function () {
        return resource_path('lang/vendor/adminlte/'.app()->getLocale());
    });
    }
}
