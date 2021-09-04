<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanTarifarioHabitacion extends Model
{
  protected $table = 'tbl_planes_tarifarios_habitaciones';
  protected $primarykey = 'id';
  protected $fillable = [
     'plan_tarifario_id',
     'tipo_habitacion_id'
  ];
}
