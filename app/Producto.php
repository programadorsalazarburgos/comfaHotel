<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    
    protected $table = 'tbl_productos';
    protected $primarykey = 'id';
    protected $fillable = [
       'nombre',
       'precio',
       'categoria_id',
       'punto_de_venta_id'
    ];
}
