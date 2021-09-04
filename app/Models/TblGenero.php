<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 16 Nov 2018 15:10:28 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TblGenero
 * 
 * @property int $id
 * @property string $nombre
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class TblGenero extends Eloquent
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'tbl_generos';
	protected $fillable = [
		'nombre'
	];
}
