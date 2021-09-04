<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CuentaAbono extends Model
{
  protected $table = 'tbl_cuentas_abonos';
  protected $primarykey = 'id';
  protected $fillable = [
     'reserva_id',
     'cuenta_id',
     'abono',
     'tipo_pago_id',
     'fecha_abono'
  ];
}
