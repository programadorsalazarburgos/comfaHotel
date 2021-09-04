<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImpuestoFactura extends Model
{
    
    protected $table = 'tbl_impuestos_facturas';
    protected $primarykey = 'id';
    protected $fillable = [
        'factura_detalle_id',
		'impuesto_id'
		
    ];
}
