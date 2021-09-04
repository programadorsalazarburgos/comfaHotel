<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Nov 2018 14:41:44 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TblCama
 * 
 * @property int $id
 * @property string $descripcion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $tbl_habitaciones
 * @property \Illuminate\Database\Eloquent\Collection $tbl_habitaciones_tipos
 *
 * @package App\Models
 */
class TblCama extends Eloquent
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $fillable = [
		'descripcion'
	];

	public function tbl_habitaciones()
	{
		return $this->hasMany(\App\Models\TblHabitacione::class, 'id_cama');
	}

	public function tbl_habitaciones_tipos()
	{
		return $this->hasMany(\App\Models\TblHabitacionesTipo::class, 'id_camas');
	}
}
