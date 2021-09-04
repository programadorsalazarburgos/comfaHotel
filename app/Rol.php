<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rol extends Model
{
    use SoftDeletes; //Implementamos
    protected $table = 'roles';
    protected $fillable = ['name','guard_name','schema'];

    // public function users()
    // {
    //     return $this->hasMany(App\User::class);
    // }

}
