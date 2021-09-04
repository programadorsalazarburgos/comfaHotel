<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImpuestoProducto extends Model
{
    
    protected $table = 'tbl_impuestos_productos';
    protected $primarykey = 'id';
    protected $fillable = [
       'nombre',
       'valor',
       'iva',
       'servicio'
    ];
}
