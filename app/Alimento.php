<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alimento extends Model
{
 
    protected $table = 'tbl_alimentos';
    protected $primarykey = 'id';
    protected $fillable = [
       'nombre',
       'valor'
    ];

}
