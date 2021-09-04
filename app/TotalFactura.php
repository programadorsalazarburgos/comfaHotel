<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TotalFactura extends Model
{


    protected $table = 'tbl_total_facturas';
    protected $primarykey = 'id';
    protected $fillable = [
       'numero',
       'fecha',
       'cliente_id',
       'grupo_id',
       'reserva_detalle_id',
       'total_a_pagar',
       'factura_id',
       'servicio',
       'cajero_id',
       'reserva_id',
       'numero_impuesto1',
       'numero_impuesto2'
    ];
}
