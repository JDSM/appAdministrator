<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleProduccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_producciones', function (Blueprint $table) {
            $table->integer('idproduccion')->unsigned();
            $table->integer('idarticulo')->unsigned();
            $table->integer('contenido')->unsigned();
            $table->decimal('costo_articulo', 11, 2);
            $table->timestamps();
            $table->foreign('idarticulo')->references('id')->on('articulos');
            $table->foreign('idproduccion')->references('id')->on('producciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_producciones');
    }
}
