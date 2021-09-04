<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanTarifario extends Model
{
  protected $table = 'tbl_planes_tarifarios';
  protected $primarykey = 'id';
  protected $fillable = [
     'nombre',
     'descripcion',
     'codigo_reservas',
     'codigo_pms',
     'estado',
     'ocupacion_tarifa',
     'menores_incluidos'
  ];
}
