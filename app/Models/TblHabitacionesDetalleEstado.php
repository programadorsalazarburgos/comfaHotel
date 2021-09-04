<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class TblHabitacionesDetalleEstado extends Model
{
	protected $table = 'tbl_habitaciones_detalle_estado';
	protected $fillable = ['id_habitacion','id_habitacion_estado','fecha'];
}
