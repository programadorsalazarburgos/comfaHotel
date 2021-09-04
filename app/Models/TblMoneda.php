<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 19 Nov 2018 21:09:28 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TblMoneda
 * 
 * @property int $id
 * @property string $nombre
 * @property string $codigo_iso
 * @property float $valor_usd
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class TblMoneda extends Eloquent
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $casts = [
		'valor_usd' => 'float'
	];

	protected $fillable = [
		'nombre',
		'codigo_iso',
		'valor_usd'
	];
}
