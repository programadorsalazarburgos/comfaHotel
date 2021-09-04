<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{

      protected $table = 'tbl_ubicaciones';
      protected $primarykey = 'id';
      protected $fillable = [
      'name'

      ];
}
