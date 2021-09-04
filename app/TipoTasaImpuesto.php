<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoTasaImpuesto extends Model
{
  protected $table = 'tbl_tipo_tasa_impuestos';
  protected $primarykey = 'id';
  protected $fillable = [
  'nombre'

  ];
}
