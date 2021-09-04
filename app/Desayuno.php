<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Desayuno extends Model
{
    
    protected $table = 'tbl_desayunos';
    protected $primarykey = 'id';
    protected $fillable = [
		'valor'
    ];
}
