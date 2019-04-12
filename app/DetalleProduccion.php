<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleProduccion extends Model
{
    protected $table = 'detalle_producciones';
    protected $fillable = [
        'idproduccion',
        'idarticulo',
        'contenido',
        'costo_articulo'
    ];
}
