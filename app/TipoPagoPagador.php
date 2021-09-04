<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoPagoPagador extends Model
{
    protected $table = 'tbl_tipo_pagos_pagadores';
    protected $primarykey = 'id';
    protected $fillable = [
       'cliente_id',
	   'tipo_pago_id',
	   'valor_pagado',
	   'grupo_id'
    ];
}
