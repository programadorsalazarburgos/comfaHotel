<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Nov 2018 14:45:34 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TblHabitacione
 *
 * @property int $id
 * @property int $id_habitacion_tipo
 * @property string $numero
 * @property int $piso
 * @property int $personas_minimo
 * @property int $personas_maximo
 * @property int $id_cama
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\Models\TblCama $tbl_cama
 * @property \App\Models\TblHabitacionesTipo $tbl_habitaciones_tipo
 * @property \Illuminate\Database\Eloquent\Collection $tbl_habitaciones_x_reservas_detalles
 *
 * @package App\Models
 */
class TblHabitacione extends Eloquent
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $casts = [
		'id_habitacion_tipo' => 'int',
		'piso' => 'int',
		'personas_minimo' => 'int',
		'personas_maximo' => 'int',
		'id_cama' => 'int'
	];

	protected $fillable = [
		'id_habitacion_tipo',
		'numero',
		'piso',
		'personas_minimo',
		'personas_maximo',
		'id_cama',
		'ubicacion_id',
		'condicion'
	];

	public function tbl_cama()
	{
		return $this->belongsTo(\App\Models\TblCama::class, 'id_cama');
	}

	public function tbl_habitaciones_tipo()
	{
		return $this->belongsTo(\App\Models\TblHabitacionesTipo::class, 'id_habitacion_tipo');
	}

	public function tbl_habitaciones_x_reservas_detalles()
	{
		return $this->hasMany(\App\Models\TblHabitacionesXReservasDetalle::class, 'id_habitacion');
	}
}
