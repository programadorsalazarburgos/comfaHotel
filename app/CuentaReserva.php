<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CuentaReserva extends Model
{
  protected $table = 'tbl_cuenta_reservas';
  protected $primarykey = 'id';
  protected $fillable = [
     'reserva_id',
     'cuenta_id',
     'fecha_hora',
     'habitacion_id',
     'concepto',
     'notas',
     'cargo',
     'abono',
     'condicion'
  ];
}
