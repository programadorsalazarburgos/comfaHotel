<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Nov 2018 14:37:18 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Categoria
 * 
 * @property int $id
 * @property string $nombre
 * @property string $descripcion
 * @property bool $condicion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class Categoria extends Eloquent
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $casts = [
		'condicion' => 'bool'
	];

	protected $fillable = [
		'nombre',
		'descripcion',
		'condicion'
	];
}
