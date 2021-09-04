<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TarifaFecha extends Model
{
  protected $table = 'tbl_tarifas_fechas';
  protected $primarykey = 'id';
  protected $fillable = [
     'fecha',
     'plan_tarifario_id',
     'tipo_habitacion_id',
     'tarifa_x_habitacion',
     'persona_extra',
     'ninos'
  ];
}
