<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class lesson extends Model
{

	protected $table = 'lessons';
    protected $fillable = [
        'user_id',
        'title',
        'body'
    ];
}
