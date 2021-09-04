<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TblConfig extends Model
{
    use SoftDeletes;
    protected $table = 'tbl_config';
    protected $dates = ['deleted_at'];
    protected $fillable = ['name', 'value'];
}
