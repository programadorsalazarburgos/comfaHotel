<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TblCmConfig extends Model
{
    use SoftDeletes; //Implementamos 
	protected $table = 'tbl_cm_config';
    protected $primarykey = 'id';
    protected $fillable = [
        'name', 'value'
    ];
}
