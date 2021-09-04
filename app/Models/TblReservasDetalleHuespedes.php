<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TblReservasDetalleHuespedes extends Model
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'tbl_reservas_detalle_huespedes';
	protected $fillable = [
		'id_cliente_huesped',
		'id_reservas_detalle',
		'es_cliente_principal',
		'reserva_grupo_id'
	];
}
