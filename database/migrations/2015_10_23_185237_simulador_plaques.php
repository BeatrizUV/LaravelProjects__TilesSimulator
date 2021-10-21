<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SimuladorPlaques extends Migration {
    /**
     * Método encargado de crear la tabla "plaques" en la base de datos.
     *
     * @return void
     */
    public function up() {
        Schema::create('plaques', function(Blueprint $table){
            $table->increments('id');
            $table->string('name', 25)->unique();
            $table->string('format', 15);
            $table->string('thumbnail', 250)->unique();
        });
    }

    /**
     * Método encargado de eliminar la tabla "plaques" de la base de datos.
     *
     * @return void
     */
    public function down() {
        Schema::drop('plaques');
    }
}
