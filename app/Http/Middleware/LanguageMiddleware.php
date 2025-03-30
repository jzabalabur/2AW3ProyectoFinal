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
        if ($request->has('lang') && in_array($request->lang, ['eu', 'es'])) {
            $locale = $request->lang;
            $this->storeLocale($locale);
        }
        elseif ($locale = $request->cookie('app_locale')) {
        }
        elseif ($locale = session('app_locale')) {
        }
        else {
            $locale = 'eu'; 
        }

        App::setLocale($locale);
        
        return $next($request)->withCookie(cookie()->forever('app_locale', $locale));
    }

    protected function storeLocale($locale)
    {
        session(['app_locale' => $locale]); 
        cookie()->forever('app_locale', $locale); 
    }
}