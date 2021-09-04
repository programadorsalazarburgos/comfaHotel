<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Nov 2018 14:47:02 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TblHabitacionesXReservasDetalle
 * 
 * @property int $id
 * @property int $id_reservas_detalle
 * @property int $id_habitacion
 * @property string $label
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\TblReservasDetalle $tbl_reservas_detalle
 * @property \App\Models\TblHabitacione $tbl_habitacione
 *
 * @package App\Models
 */
class TblHabitacionesXReservasDetalle extends Eloquent
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'tbl_habitaciones_x_reservas_detalle';

	protected $casts = [
		'id_reservas_detalle' => 'int',
		'id_habitacion' => 'int'
	];

	protected $fillable = [
		'id_reservas_detalle',
		'id_habitacion',
		'label'
	];

	public function tbl_reservas_detalle()
	{
		return $this->belongsTo(\App\Models\TblReservasDetalle::class, 'id_reservas_detalle');
	}

	public function tbl_habitacione()
	{
		return $this->belongsTo(\App\Models\TblHabitacione::class, 'id_habitacion');
	}
}
