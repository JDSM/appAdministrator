<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    protected $table = 'producciones';
    protected $fillable = [
        'idreceta',
        'cantidad_p',
        'costo_total',
        'cantidad_artprinc'
    ];
}
