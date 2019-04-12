<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CostoArticulo extends Model
{
    protected $table = 'costo_articulos';
    protected $fillable = [
        'idarticulo',
        'cantidad',
        'costo_articulo',
        'id_detalle_ingreso',
        'contenido',
        'idventa',
        'cant_venta'
    ];
}
