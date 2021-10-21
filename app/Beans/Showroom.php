<?php

namespace Simulador\Beans;

use Illuminate\Database\Eloquent\Model;

/**
 * Clase encargada de encapsular los datos de los distribuidores.
 * Almacena los datos en la base de datos.
 * 
 * @author Beatriz Urbano Vega
 */
class Showroom extends Model
{
     /**
     * La tabla de la base de datos utilizada por el modelo.
     *
     * @var string
     */
    protected $table = 'showrooms';

    /**
     * Los atributos a los que se permite el acceso en la base de datos.
     *
     * @var array
     */
    protected $fillable = ['company', 'email', 'telephone', 'logo', 'website', 'lang', 'slug'];
    
    /**
     * Para este modelo no se registrarán las fechas de creación y modificación en la base de datos
     * @var boolean 
     */
    public $timestamps = false;
}
