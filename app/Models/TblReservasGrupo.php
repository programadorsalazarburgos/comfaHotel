<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TblReservasGrupo extends Model
{
    use SoftDeletes;
  protected $dates = ['deleted_at'];
	protected $table = 'tbl_reservas_grupo';

	protected $fillable = [
        'id_reservas',
        'id_reservas_estado',
        'check_in_fecha',
        'check_out_fecha',
        'tipo_habitacion_id',
        'huespedes_cantidad'
	];
}
