<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TblHotel extends Model
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
    // protected $table = 'master.tbl_hoteles';
    protected $table = 'tbl_hoteles';
    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'nit',
        'direccion',
        'responsable_nombre',
        'responsable_telefono',
        'api_user',
        'api_password',
        'api_code',
        'comentario',
        'schema'
	];
}
