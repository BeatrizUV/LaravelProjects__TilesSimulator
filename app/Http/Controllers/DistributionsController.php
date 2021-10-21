<?php

namespace Simulador\Http\Controllers;

use Simulador\Http\Controllers\Controller;
use Simulador\Beans\Showroom;
use View;
use Validator;

/**
 * Clase encargada de mostrar los simuladores personalizados de los distribuidores.
 * 
 * @author Beatriz Urbano Vega
 */
class DistributionsController extends Controller
{    
    /**
     * SMétodo encargado de cargar la página principal del simulador.
     * 
     * @param string $slug
     * @return View
     */
    public function index($slug)
    {
        $error = true;
        // Establecemos el patrón de validación del slug correspondiente al nombre del distribuidor
        $rules = array('slug'=> 'required|Min:5|Max:100|Alpha|exists:showrooms,slug');   
        // Validamos el slug
        $validator = Validator::make(['slug' => $slug], $rules);
        
        // Si la validación es correcta
        if (!$validator->fails()) {            
            // Obtenemos los datos del distribuidor
            $showroom = Showroom::where('slug', $slug)->first();
            $error = false;
        }
        
        // Si hay errores durante el proceso
        if ($error) {
            // Devolvemos al usuario a la página principal del simulador
            return redirect()->route('index');
        }
        else {
            // Y si no hay fallos, cargamos la vista del simulador personalizado
            return View::make('distributions')->with('showroom', $showroom); 
        }
    }
}