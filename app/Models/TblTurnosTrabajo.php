<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Nov 2018 14:53:25 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

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
class TblTurnosTrabajo extends Eloquent
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'tbl_turnos_trabajo';
/*
	protected $dates = [
		'hora_inicio',
		'hora_fin'
	];*/

	protected $fillable = [
		'nombre',
		'hora_inicio',
		'hora_fin'
	];
}
