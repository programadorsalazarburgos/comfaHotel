<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoPago extends Model
{
    protected $table = 'tbl_tipo_pagos';
    protected $primarykey = 'id';
    protected $fillable = [
       'nombre'
    ];
}
