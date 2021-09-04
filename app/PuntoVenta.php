<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PuntoVenta extends Model
{
    

    protected $table = 'tbl_puntos_ventas';
    protected $primarykey = 'id';
    protected $fillable = [
       'nombre'
    ];
}
