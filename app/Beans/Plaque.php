<?php

namespace Simulador\Beans;

use Illuminate\Database\Eloquent\Model;

/**
 * Clase encargada de encapsular los datos de las placas.
 * Almacena los datos en la base de datos.
 * 
 * @author Beatriz Urbano Vega
 */
class Plaque extends Model
{
     /**
     * La tabla de la base de datos utilizada por el modelo.
     *
     * @var string
     */
    protected $table = 'plaques';

    /**
     * Los atributos a los que se permite el acceso en la base de datos.
     *
     * @var array
     */
    protected $fillable = ['name', 'format', 'thumbnail'];
    
    /**
     * El resto de los atributos de la placa.
     * @var type 
     */
    protected $guarded = ['piecesList'];
    
    /**
     * Para este modelo no se registrarán las fechas de creación y modificación en la base de datos
     * @var boolean 
     */
    public $timestamps = false;
}
