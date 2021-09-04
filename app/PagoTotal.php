<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagoTotal extends Model
{

    protected $table = 'tbl_pagos_totales';
    protected $primarykey = 'id';
    protected $fillable = [
       'numero',
       'factura_id',
       'cliente_id',
       'total_a_pagar',
       'valor_pagado',
       'cajero_id',
       'reserva_id'
    ];
}
