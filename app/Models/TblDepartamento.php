<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Nov 2018 14:43:58 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TblDepartamento
 * 
 * @property int $id
 * @property string $nombre_departamento
 * @property int $id_pais
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\TblPaise $tbl_paise
 * @property \Illuminate\Database\Eloquent\Collection $tbl_clientes
 *
 * @package App\Models
 */
class TblDepartamento extends Eloquent
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $casts = [
		'id_pais' => 'int'
	];
	protected $table = 'master.tbl_departamentos';
	protected $fillable = [
		'nombre_departamento',
		'id_pais'
	];

	public function tbl_paise()
	{
		return $this->belongsTo(\App\Models\TblPaise::class, 'id_pais');
	}

	public function tbl_clientes()
	{
		return $this->hasMany(\App\Models\TblCliente::class, 'id_departamento_factura');
	}
}
