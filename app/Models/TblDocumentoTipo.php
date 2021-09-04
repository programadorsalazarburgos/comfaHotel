<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Nov 2018 14:44:47 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TblDocumentoTipo
 * 
 * @property int $id
 * @property string $descripcion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $tbl_clientes
 *
 * @package App\Models
 */
class TblDocumentoTipo extends Eloquent
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'tbl_documento_tipo';

	protected $fillable = [
		'descripcion'
	];

	public function tbl_clientes()
	{
		return $this->hasMany(\App\Models\TblCliente::class, 'id_documento_tipo');
	}
}
