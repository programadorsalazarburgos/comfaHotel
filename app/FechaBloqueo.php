<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FechaBloqueo extends Model
{
  protected $table = 'tbl_fechas_bloqueos';
  protected $primarykey = 'id';
  protected $fillable = [
     'habitacion_id',
     'fecha_desde',
     'fecha_hasta'
  ];
}
