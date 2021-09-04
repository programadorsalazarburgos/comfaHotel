<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MensajesFactura extends Model
{
  protected $table = 'tbl_mensajes_facturas';
  protected $primarykey = 'id';
  protected $fillable = [
   'factura_id',
   'cliente_id',
   'mensaje'
  ];
}
