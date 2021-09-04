<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoPagoTotal extends Model
{

    protected $table = 'tbl_tipo_pagos_totales';
    protected $primarykey = 'id';
    protected $fillable = [
       'cliente_id',
       'factura_id',
       'valor_a_pagar',
       'valor_pagado',
       'servicio',
       'cajero_id',
       'reserva_id'
    ];
}
