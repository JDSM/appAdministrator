<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCostoArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('costo_articulos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idarticulo')->unsigned();
            $table->integer('cantidad')->unsigned();
            $table->decimal('costo_articulo', 11, 2);
            $table->integer('id_detalle_ingreso')->unsigned();
            $table->integer('contenido')->unsigned();
            $table->integer('idventa')->unsigned();
            $table->integer('cant_venta')->unsigned();
            $table->timestamps();
            $table->foreign('idarticulo')->references('id')->on('articulos');
            $table->foreign('id_detalle_ingreso')->references('id')->on('detalle_ingreso');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('costo_articulos');
    }
}
