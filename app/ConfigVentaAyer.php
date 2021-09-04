<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfigVentaAyer extends Model
{
    
    protected $table = 'tbl_configventasAyer';
    protected $primarykey = 'id';
    protected $fillable = [
       'valor_ayer'
    ];
}
