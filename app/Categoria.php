<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //protected $table = 'categorias'; // no se implementa  este metodo ya que la tabla se llama igual que el modelo
    //protected $primaryKey = 'id'; // no se implementa ya que eloquent asume que toda tabla tiene un id como primaryKey
    protected $fillable = ['nombre','descripcion','condicion'];

    public function articulos()
    {
        return $this->hasMany('App\Articulo');
    }
    
}
