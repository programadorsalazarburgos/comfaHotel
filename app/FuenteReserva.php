<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FuenteReserva extends Model
{
    
    protected $table = 'tbl_fuentes_reservas';
    protected $primarykey = 'id';
    protected $fillable = [
       'nombre',
       'id_ota'
    ];

}
