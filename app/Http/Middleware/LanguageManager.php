<?php

namespace Simulador\Http\Middleware;

use Closure;
use App;
use Session;

/**
 * Clase encargada de gestionar el idioma de la aplicación.
 * Clase accesible sólo desde el propio framework.
 * 
 * @author Beatriz Urbano Vega 
 */
class LanguageManager
{
    /**
     * Método que lanza el proceso de gestión de idiomas.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {     
        // Recogemos el idioma por defecto de la aplicación
        $lang = config('app.fallback_locale');
        
        // Si detectamos un nuevo idioma en la URL
        if (isset($request['lang'])) {            
            // Revisamos si dicho idioma es uno de los listados en la configuración de la aplicación
            if (in_array($request['lang'], config('app.locale'))) {
                // Y lo asignamos
                $lang = $request['lang'];
            }
        }
        else {
            // Si no detectamos ningún idioma en la URL
            // Miramos si tenemos el idioma asignado en la sesión
            if(Session::has('lang')) {
                // Y si es así, recogemos el idioma de la sesión
                $lang = Session::get('lang');
            }
        }
        
        // Asignamos el idioma a la aplicación
        $this->setLanguage($lang);
        
        // Y dejamos que el proceso solicitado por el usuario siga ejecutándose
        return $next($request);
    }
    
    /**
     * Método encargado de asignar el idioma de la aplicación.
     * @param string $lang
     */
    private function setLanguage($lang) {
        // Asignamos el idioma de la aplicación
        App::setLocale($lang);
        // Y guardamos el idioma seleccionado en la sesión para futuras consultas
        Session::set('lang', $lang);
    }
}
