<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImpuestoTasa extends Model
{
  protected $table = 'tbl_impuestos_tasas';
  protected $primarykey = 'id';
  protected $fillable = [
     'importe_id',
     'nombre',
     'valor',
     'hospedaje',
     'producto_servicio',
     'formato_id',
     'estado'
  ];
}
