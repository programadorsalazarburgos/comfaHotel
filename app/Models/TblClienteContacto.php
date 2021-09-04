<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 19 Nov 2018 16:12:53 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TblClienteContacto
 * 
 * @property int $id
 * @property int $cliente_id
 * @property int $contacto_tipo_id
 * @property string $informacion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\TblCliente $tbl_cliente
 * @property \App\Models\TblContactoTipo $tbl_contacto_tipo
 *
 * @package App\Models
 */
class TblClienteContacto extends Eloquent
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'tbl_cliente_contacto';

	protected $casts = [
		'cliente_id' => 'int',
		'contacto_tipo_id' => 'int'
	];

	protected $fillable = [
		'cliente_id',
		'contacto_tipo_id',
		'informacion'
	];

	public function tbl_cliente()
	{
		return $this->belongsTo(\App\Models\TblCliente::class, 'cliente_id');
	}

	public function tbl_contacto_tipo()
	{
		return $this->belongsTo(\App\Models\TblContactoTipo::class, 'contacto_tipo_id');
	}
}
