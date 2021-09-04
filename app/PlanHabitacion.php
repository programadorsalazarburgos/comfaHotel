<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanHabitacion extends Model
{
    
    protected $table = 'tbl_plan_habitaciones';
    protected $primarykey = 'id';
    protected $fillable = [
       'habitacion_id',
       'plan_id'
    ];

}
