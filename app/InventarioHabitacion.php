<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventarioHabitacion extends Model
{
  protected $table = 'tbl_inventarios_habitaciones';
  protected $primarykey = 'id';
  protected $fillable = [
  'tipo_habitacion_id',
  'habitacion_id',
  'disponibilidad',
  'color',
  'start',
  'end',
  'estado'
  ];
}
