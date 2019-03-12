<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE TRIGGER tr_updStockIngreso AFTER INSERT ON detalle_ingreso FOR EACH ROW BEGIN UPDATE articulos SET stock =  stock + NEW.cantidad WHERE articulos.id = NEW.idarticulo; END;');
        DB::unprepared('CREATE TRIGGER tr_updStockIngresoAnular AFTER UPDATE ON ingresos FOR EACH ROW BEGIN UPDATE articulos a  JOIN detalle_ingreso di ON di.idarticulo = a.id AND di.idingreso = NEW.id SET a.stock = a.stock - di.cantidad; END;');
        DB::unprepared('CREATE TRIGGER tr_updStockVenta AFTER INSERT ON detalle_ventas FOR EACH ROW BEGIN UPDATE articulos SET stock =  stock - NEW.cantidad WHERE articulos.id = NEW.idarticulo; END;');
        DB::unprepared('CREATE TRIGGER tr_updStockVentaAnular AFTER UPDATE ON ventas FOR EACH ROW BEGIN UPDATE articulos a  JOIN detalle_ventas dv ON dv.idarticulo = a.id AND dv.idventa = NEW.id SET a.stock = a.stock + dv.cantidad; END;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER tr_updStockIngreso');
    }
}
