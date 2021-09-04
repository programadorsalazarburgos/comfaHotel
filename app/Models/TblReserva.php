<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Nov 2018 14:49:15 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TblReserva
 *
 * @property int $id
 * @property int $id_cliente
 * @property \Carbon\Carbon $check_in_fecha
 * @property \Carbon\Carbon $check_in_hora
 * @property int $check_out_fecha
 * @property \Carbon\Carbon $check_out_hora
 * @property int $id_reserva_tipo
 * @property string $codigo
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\Models\TblCliente $tbl_cliente
 * @property \App\Models\TblReservasTipo $tbl_reservas_tipo
 *
 * @package App\Models
 */
class TblReserva extends Eloquent
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $casts = [
		'id_cliente' => 'int',
		'id_reserva_tipo' => 'int'
	];
	protected $fillable = [
		'id_cliente',
		'check_in_fecha',
		'check_out_fecha',
		'id_reserva_tipo',
		'codigo',
		'fuente_reserva_id',
		'huespedes_cantidad'
	];

	public function tbl_cliente()
	{
		return $this->belongsTo(\App\Models\TblCliente::class, 'id_cliente');
	}

	public function tbl_reservas_tipo()
	{
		return $this->belongsTo(\App\Models\TblReservasTipo::class, 'id_reserva_tipo');
	}
}
