<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProduccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idreceta')->unsigned();
            $table->integer('cantidad_p')->unsigned();
            $table->decimal('costo_total', 11, 2);
            $table->integer('cantidad_artprinc');
            $table->timestamps();
            $table->foreign('idreceta')->references('id')->on('recetas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('producciones');
    }
}
