<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Nov 2018 14:47:47 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TblImpuesto
 *
 * @property int $id
 * @property int $nombre
 * @property float $valor
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property \Illuminate\Database\Eloquent\Collection $tbl_reservas_x_impuestos
 *
 * @package App\Models
 */
class TblImpuesto extends Eloquent
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $casts = [
		'valor' => 'float'
	];

	protected $fillable = [
		'nombre',
		'valor',
		'principal',
		'ish',
		'servicio'

	];

	public function tbl_reservas_x_impuestos()
	{
		return $this->hasMany(\App\Models\TblReservasXImpuesto::class, 'id_impuesto');
	}
}
