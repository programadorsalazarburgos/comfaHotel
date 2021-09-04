<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Nov 2018 14:49:59 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TblReservasDetalle
 *
 * @property int $id
 * @property int $id_reservas
 * @property int $id_habitacion_tipo
 * @property int $habitaciones_cantidad
 * @property int $huespedes_cantidad
 * @property int $habitacion_precio_total
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property \Illuminate\Database\Eloquent\Collection $tbl_habitaciones_x_reservas_detalles
 * @property \Illuminate\Database\Eloquent\Collection $tbl_reservas_x_impuestos
 *
 * @package App\Models
 */
class TblReservasDetalle extends Eloquent
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'tbl_reservas_detalle';

	protected $casts = [
		'id_habitacion_tipo' => 'int',
		'habitaciones_cantidad' => 'int',
		'huespedes_cantidad' => 'int',
		'habitacion_precio_total' => 'int',
		'adultos_cantidad' => 'int',
		'ninos_cantidad' => 'int',
		'id_reservas_grupo' => 'int',
		'infantes_cantidad'=> 'int',
		'id_reserva_detalle_estado_habitacion'=>'int'
	];

	protected $fillable = [
		'id_reservas_grupo',
		'id_habitacion_tipo',
		'id_habitacion',
		'adultos_cantidad',
		'ninos_cantidad',
		'infantes_cantidad',
		'habitacion_precio_total',
		'requisitos',
		'comentarios',
		'check_in_fecha',
		'check_out_fecha',
		'facturado',
		'id_reserva_detalle_estado_habitacion',
		'salida',
	];

	public function tbl_habitaciones_x_reservas_detalles()
	{
		return $this->hasMany(\App\Models\TblHabitacionesXReservasDetalle::class, 'id_reservas_detalle');
	}

	public function tbl_reservas_x_impuestos()
	{
		return $this->hasMany(\App\Models\TblReservasXImpuesto::class, 'id_reserva_detalle');
	}
}
