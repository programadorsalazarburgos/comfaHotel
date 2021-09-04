<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Nov 2018 14:46:18 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TblHabitacionesTipo
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property int $persona_minimo
 * @property int $persona_maximo
 * @property int $id_camas
 * @property int $id_tipo_habitacion_superbooking
 * @property float $precion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\Models\TblCama $tbl_cama
 * @property \Illuminate\Database\Eloquent\Collection $tbl_habitaciones
 *
 * @package App\Models
 */
class TblHabitacionesTipo extends Eloquent
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'tbl_habitaciones_tipo';

	protected $casts = [
		'persona_minimo' => 'int',
		'persona_maximo' => 'int',
		'id_camas' => 'int'
	];

	protected $fillable = [
		'codigo',
		'room_type',
		'nombre',
		'persona_minimo',
		'persona_maximo',
		'id_camas',
		'room_type'
	];

	public function tbl_cama()
	{
		return $this->belongsTo(\App\Models\TblCama::class, 'id_camas');
	}

	public function tbl_habitaciones()
	{
		return $this->hasMany(\App\Models\TblHabitacione::class, 'id_habitacion_tipo');
	}
}
