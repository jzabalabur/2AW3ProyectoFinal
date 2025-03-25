<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class LanguageMiddleware
{
    public function handle($request, Closure $next)
    {
        
        // Cambia a español solo si hay parámetro ?lang=es
        if ($request->has('lang') && $request->lang === 'eu') {
            $locale = 'eu';
            $this->storeLocale($request->lang);
        } else if ($request->has('lang') && $request->lang === 'es') {
            $locale = 'es';
            $this->storeLocale($request->lang);
        } else {
            $locale = $this->getStoredLocale();
        }

        
        App::setLocale($locale);
        
        return $next($request);
    }

    protected function storeLocale($locale)
    {
        Session::put('app_locale', $locale);
    }

    protected function getStoredLocale()
    {
    return Session::get('app_locale');
    }
}