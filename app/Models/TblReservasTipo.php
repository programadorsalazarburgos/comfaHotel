<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Nov 2018 14:50:44 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TblReservasTipo
 * 
 * @property int $id
 * @property string $nombre
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $tbl_reservas
 *
 * @package App\Models
 */
class TblReservasTipo extends Eloquent
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'tbl_reservas_tipo';

	protected $fillable = [
		'nombre'
	];

	public function tbl_reservas()
	{
		return $this->hasMany(\App\Models\TblReserva::class, 'id_reserva_tipo');
	}
}
