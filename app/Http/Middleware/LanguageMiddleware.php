<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageMiddleware
{
    public function handle($request, Closure $next)
    {
        // Si viene el parámetro lang en la URL
        if ($request->has('lang') && in_array($request->lang, ['eu', 'es'])) {
            $locale = $request->lang;
            App::setLocale($locale);
            $this->storeLocale($locale);
            
            // Crear la cookie con el nuevo idioma
            $cookie = cookie()->forever('app_locale', $locale);
            
            // Procesar la respuesta
            $response = $next($request);
            
            // Adjuntar la cookie a la respuesta
            return $response->withCookie($cookie);
        }
        
        // Si no viene en la URL, chequear cookie o sesión
        if ($locale = $request->cookie('app_locale')) {
            App::setLocale($locale);
        } elseif ($locale = session('app_locale')) {
            App::setLocale($locale);
        } else {
            $locale = 'eu'; // Idioma por defecto
            App::setLocale($locale);
        }
        
        return $next($request);
    }
    
    protected function storeLocale($locale)
    {
        session(['app_locale' => $locale]);
    }
}