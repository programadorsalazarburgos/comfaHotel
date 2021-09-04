<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use SoftDeletes; //Implementamos 
    protected $fillable=['type','notifiable_id','notifiable_type','data'];
}

