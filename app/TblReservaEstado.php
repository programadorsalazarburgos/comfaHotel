<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TblReservaEstado extends Model
{
    
    protected $table = 'tbl_reservas_estado';
    protected $primarykey = 'id';
    protected $fillable = [
       'descripcion',
	   'color',
	   'orden'
    ];
}
