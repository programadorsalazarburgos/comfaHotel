<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactoTipo extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'tbl_contacto_tipo';
    protected $fillable = [
        'nombre'
    ];
}
