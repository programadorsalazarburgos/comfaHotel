<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 22 Nov 2018 16:25:40 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TblPaise
 * 
 * @property int $id
 * @property string $iso
 * @property string $nombre_pais
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $tbl_clientes
 * @property \Illuminate\Database\Eloquent\Collection $tbl_departamentos
 *
 * @package App\Models
 */
class TblPaise extends Eloquent
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'tbl_paises';
	protected $fillable = [
		'iso',
		'nombre_pais'
	];

	public function tbl_clientes()
	{
		return $this->hasMany(\App\Models\TblCliente::class, 'id_pais_residencia');
	}

	public function tbl_departamentos()
	{
		return $this->hasMany(\App\Models\TblDepartamento::class, 'id_pais');
	}
}
