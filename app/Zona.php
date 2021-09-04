<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
  protected $table = 'tbl_zonas_horarias';
  protected $fillable = [
        'name'
  ];
}
