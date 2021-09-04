<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
  protected $table = 'tbl_inventarios';
  protected $primarykey = 'id';
  protected $fillable = [
  'tipo_habitacion_id',
  'disponibilidad',
  'color',
  'start',
  'end'
  ];
}
