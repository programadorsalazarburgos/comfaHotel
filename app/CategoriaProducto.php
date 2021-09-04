<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoriaProducto extends Model
{
    
    protected $table = 'tbl_categorias';
    protected $primarykey = 'id';
    protected $fillable = [
       'nombre'
    ];
}
