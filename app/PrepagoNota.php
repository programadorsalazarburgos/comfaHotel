<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrepagoNota extends Model
{
  protected $table = 'tbl_prepagos_notas';
  protected $primarykey = 'id';
  protected $fillable = [
     'tipo_pago_id',
     'valor_pagado',
     'reserva_id',
     'cliente_id',

  ];
}
