<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
  protected $table = 'tbl_cuentas';
  protected $primarykey = 'id';
  protected $fillable = [
     'nombre'
  ];
}
