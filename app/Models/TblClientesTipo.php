<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Nov 2018 14:43:14 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TblClientesTipo
 * 
 * @property int $id
 * @property string $nombre
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $tbl_clientes
 *
 * @package App\Models
 */
class TblClientesTipo extends Eloquent
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'tbl_clientes_tipo';

	protected $fillable = [
		'nombre'
	];

	public function tbl_clientes()
	{
		return $this->hasMany(\App\Models\TblCliente::class, 'id_clientes_tipo');
	}
}
