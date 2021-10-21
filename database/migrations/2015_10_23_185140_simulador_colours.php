<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SimuladorColours extends Migration {
    /**
     * Método encargado de crear la tabla "colours" en la base de datos.
     *
     * @return void
     */
    public function up() {
        Schema::create('colours', function(Blueprint $table){
            $table->increments('id');
            $table->string('name', 4)->unique();
            $table->string('image', 100)->unique();
        });
    }

    /**
     * Método encargado de eliminar la tabla "colours" de la base de datos.
     *
     * @return void
     */
    public function down() {
        Schema::drop('colours');
    }
}
