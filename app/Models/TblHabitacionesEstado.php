<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class TblHabitacionesEstado extends Model
{
	protected $table = 'tbl_habitaciones_estado';
	protected $fillable = ['nombre','color'];
}
