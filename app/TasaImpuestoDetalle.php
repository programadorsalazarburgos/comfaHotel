<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TasaImpuestoDetalle extends Model
{
  protected $table = 'tbl_tasas_impuestos_detalles';
  protected $primarykey = 'id';
  protected $fillable = [
     'reserva_detalle_id',
     'impuesto_tasa_id'
  ];
}
