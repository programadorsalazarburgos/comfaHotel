<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use SoftDeletes; //Implementamos 
    protected $fillable = ['nombre','descripcion','condicion'];

    public function articulos()
    {
        return $this->hasMany(Articulo::class, 'idcategoria');
    }
}
