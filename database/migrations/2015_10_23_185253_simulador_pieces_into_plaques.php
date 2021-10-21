<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SimuladorPiecesIntoPlaques extends Migration {
    /**
     * Método encargado de crear la tabla "pieces_into_plaques" en la base de datos.
     *
     * @return void
     */
    public function up() {
        Schema::create('pieces_into_plaques', function(Blueprint $table){
           $table->text('nodes'); 
           $table->integer('piece_id')->unsigned();
           $table->integer('plaque_id')->unsigned();
           $table->boolean('locked')->default(false);
           $table->string('default_colour', 4);
           
           // Claves foráneas
           $table->foreign('piece_id')->references('id')->on('pieces')->onDelete('cascade');
           $table->foreign('plaque_id')->references('id')->on('plaques')->onDelete('cascade');
        });
    }

    /**
     * Método encargado de eliminar la tabla "pieces_into_plaques" en la base de datos.
     *
     * @return void
     */
    public function down() {
        Schema::drop('pieces_into_plaques');
    }
}
