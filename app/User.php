<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable, HasRoles;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';
    protected $fillable = [
        'nombres',
        'tipo_documento',
        'documento',
        'telefono',
        'email',
        'usuario',
        'schema',
        'password',
        'activo',
        'hotel_id',
        'remember_token'     
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
        'created_at','updated_at','deleted_at','api_password','schema','activo'
    ];

 

    public function rol(){
        return $this->belongsTo(\App\Rol::class);
    }
    public function tbl_hoteles()
	{
		return $this->hasMany('\App\Models\TblHotel');
	}
 

    public function friendsOfMine()
    {
        return $this->belongsToMany('App\User', 'friends', 'user_id', 'friend_id');
    } 

    public function friendOf()
    {
        return $this->belongsToMany('App\User', 'friends', 'friend_id', 'user_id');
    }

    public function friends()
    {
        return $this->friendsOfMine->merge($this->friendOf);
    }

    public function getAllPermissionsAttribute() {
      $permissions = [];
        foreach (Permission::all() as $permission) {
          if (Auth::user()->can($permission->name)) {
            $permissions[] = $permission->name;
          }
        }
        return $permissions;
    }    

}
