<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Nov 2018 14:53:25 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TblTurnosTrabajo
 * 
 * @property int $id
 * @property string $nombre
 * @property \Carbon\Carbon $hora_inicio
 * @property \Carbon\Carbon $hora_fin
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class TblReservaPagador extends Eloquent
{
	protected $table = 'tbl_reservas_pagadores';

	protected $fillable = [
		'cliente_id',
		'reserva_detalle_id',
		'valor_a_pagar',
		'reserva_grupo_id'
	];
}
