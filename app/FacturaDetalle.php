<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacturaDetalle extends Model
{
     protected $table = 'tbl_facturas_detalles';
    protected $primarykey = 'id';
    protected $fillable = [
        'factura_id',
        'reserva_detalle_id',
		    'check_in_fecha',
	    	'id_habitacion_tipo',
		    'id_habitacion',
		    'precio_unitario',
		    'precio_bruto',
        'descuento',
        'reserva_id',
        'ish',
        'cajero_id'
    ];
}
