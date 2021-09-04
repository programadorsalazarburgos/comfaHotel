<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoPagoTotalDetalle extends Model
{
   
    protected $table = 'tbl_tipo_pagos_totales_detalles';
    protected $primarykey = 'id';
    protected $fillable = [
       'tipo_pago_total_id',
       'tipo_pago_id',
       'valor_pagado'
    ];
}
