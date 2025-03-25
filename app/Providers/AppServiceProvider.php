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
    }
}
