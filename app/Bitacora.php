<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
  protected $table = 'tbl_bitacoras';
  protected $primarykey = 'id';
  protected $fillable = [
     'user_id',
     'reserva_id',
     'grupo_id',
     'habitacion_id',
     'fecha_llegada_anterior',
     'fecha_salida_anterior',
     'fecha_llegada_actual',
     'fecha_salida_actual',
     'tipo_movimiento',
     'xml',
     'respuesta'
  ];
}
