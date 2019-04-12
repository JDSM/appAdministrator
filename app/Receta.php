<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    protected $table = 'recetas';
    protected $fillable = [
        'idarticulo',
        'principal',
        'contenido',
        'idingrediente'
    ];
}
