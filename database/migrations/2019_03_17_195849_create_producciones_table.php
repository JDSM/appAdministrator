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
            $table->integer('idarticulo')->unsigned();
            $table->integer('cantidad_p')->unsigned();
            $table->decimal('costo_total', 11, 2);
            $table->integer('cantidad_artprinc');
            $table->integer('idingrediente_principal');
            $table->timestamps();
            $table->foreign('idarticulo')->references('id')->on('articulos');
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
