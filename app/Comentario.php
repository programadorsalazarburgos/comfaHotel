<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $table = 'tbl_comentarios';
    protected $primarykey = 'id';
    protected $fillable = [
       'grupo_id',
       'comentario'
    ];

}
