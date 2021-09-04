<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagadorFactura extends Model
{

    protected $table = 'tbl_pagadores_facturas';
    protected $primarykey = 'id';
    protected $fillable = [
		'cliente_id',
		'valor_a_pagar',
		'valor_pagado',
		'grupo_id',
		'valor_a_pagar_neto',
		'fecha_facturado',
		'facturado',
		'cajero_id'
    ];
}
