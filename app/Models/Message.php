<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Nov 2018 14:40:54 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Message
 * 
 * @property int $id
 * @property int $from
 * @property int $to
 * @property bool $read
 * @property string $text
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class Message extends Eloquent
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $casts = [
		'from' => 'int',
		'to' => 'int',
		'read' => 'bool'
	];

	protected $fillable = [
		'from',
		'to',
		'read',
		'text'
	];
}
