<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormatoAplicacion extends Model
{
  protected $table = 'tbl_formato_aplicacion';
  protected $primarykey = 'id';
  protected $fillable = [
  'name'

  ];
}
