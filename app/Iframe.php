<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Iframe extends Model
{
  protected $table = 'tbl_iframes';
  protected $primarykey = 'id';
  protected $fillable = [
     'iframe'
  ];
}
