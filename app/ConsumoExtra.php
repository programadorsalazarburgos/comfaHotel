<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsumoExtra extends Model
{


    protected $table = 'tbl_consumos_extras';
    protected $primarykey = 'id';
    protected $fillable = [
		'producto_id',
		'producto',
		'unidad',
		'cuenta_id',
		'total',
		'cuenta_reserva_id'
    ];
}
