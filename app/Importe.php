<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Importe extends Model
{
  protected $table = 'tbl_importes';
  protected $primarykey = 'id';
  protected $fillable = [
  'name'

  ];
}
