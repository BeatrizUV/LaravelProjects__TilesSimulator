<?php

namespace Simulador\Beans;

use Illuminate\Database\Eloquent\Model;

/**
 * Clase que encapsula los datos de un color.
 * Almacena datos dentro de la base de datos.
 * 
 * @author Beatriz Urbano Vega 
 */
class Colour extends Model
{
    /**
     * La tabla de la base de datos utilizada por el modelo.
     *
     * @var string
     */
    protected $table = 'colours';

    /**
     * Los atributos a los que se permite el acceso en la base de datos.
     *
     * @var array
     */
    protected $fillable = ['name', 'image'];   
    
    /**
     * Para este modelo no se registrarán las fechas de creación y modificación en la base de datos
     * @var boolean 
     */
    public $timestamps = false;
}
