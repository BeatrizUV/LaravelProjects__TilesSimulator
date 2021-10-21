<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SimuladorShowrooms extends Migration {
    /**
     * Método encargado de crear la tabla "showrooms" en la base de datos.
     *
     * @return void
     */
    public function up() {
        Schema::create('showrooms', function(Blueprint $table){
            $table->increments('id');
            $table->string('company', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('logo', 250);
            $table->string('telephone', 25)->unique();
            $table->string('website', 100)->unique();
            $table->string('lang', 2)->default('es');
            $table->string('slug', 100)->unique();
        });
    }

    /**
     * Método encargado de eliminar la tabla "showrooms" en la base de datos.
     *
     * @return void
     */
    public function down() {
        Schema::drop('showrooms');
    }
}
