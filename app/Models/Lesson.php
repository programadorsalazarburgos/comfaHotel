<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Nov 2018 14:39:51 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Lesson
 * 
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $body
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class Lesson extends Eloquent
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'title',
		'body'
	];
}
