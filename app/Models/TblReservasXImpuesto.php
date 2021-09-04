<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Nov 2018 14:51:29 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TblReservasXImpuesto
 * 
 * @property int $id
 * @property int $id_reserva_detalle
 * @property int $id_impuesto
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\TblReservasDetalle $tbl_reservas_detalle
 * @property \App\Models\TblImpuesto $tbl_impuesto
 *
 * @package App\Models
 */
class TblReservasXImpuesto extends Eloquent
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $casts = [
		'id_reserva_detalle' => 'int',
		'id_impuesto' => 'int'
	];

	protected $fillable = [
		'id_reserva_detalle',
		'id_impuesto'
	];

	public function tbl_reservas_detalle()
	{
		return $this->belongsTo(\App\Models\TblReservasDetalle::class, 'id_reserva_detalle');
	}

	public function tbl_impuesto()
	{
		return $this->belongsTo(\App\Models\TblImpuesto::class, 'id_impuesto');
	}
}
