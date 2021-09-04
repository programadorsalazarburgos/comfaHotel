<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'tbl_facturas';
    protected $primarykey = 'id';
    protected $fillable = [
     'id_reservas_grupo',
	   'total_factura',
	   'total_neto',
	   'ish',
	   'servicio',
     'cajero_id',
     'reserva_id'
    ];
}
