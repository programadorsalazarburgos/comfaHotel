<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Friend extends Model
{
    use SoftDeletes; //Implementamos 
    protected $fillable = [
        'user_id',
        'friend_id'
       
    ];
}
