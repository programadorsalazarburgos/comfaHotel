<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Nov 2018 14:38:08 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Chat
 * 
 * @property int $id
 * @property int $user_id
 * @property int $friend_id
 * @property string $chat
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class Chat extends Eloquent
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $casts = [
		'user_id' => 'int',
		'friend_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'friend_id',
		'chat'
	];
}
