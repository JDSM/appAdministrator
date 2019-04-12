<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cartera extends Model
{
    protected $filelable = [
        'idcliente',
        'idventa',
        'estado',
        'abono',
        'deuda',
        'prox_pago'
    ];

    public function persona()
    {
        return $this->belongsTo('App\Persona');
    }
    public function venta()
    {
        return $this->belongsTo('App\Venta');
    }
}
