<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SimuladorPieces extends Migration {
    /**
     * Método encargado de crear la tabla "pieces" en la base de datos.
     *
     * @return void
     */
    public function up() {
        Schema::create('pieces', function(Blueprint $table){
            $table->increments('id');
            $table->string('name', 25)->unique();
            $table->string('image', 100)->unique();
        });
    }

    /**
     * Método encargado de eliminar la tabla "pieces" de la base de datos.
     *
     * @return void
     */
    public function down() {
        Schema::drop('pieces');
    }
}
