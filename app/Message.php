<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Message extends Model
{
    use SoftDeletes; //Implementamos 
    protected $guarded = [];
    public function fromContact()
    {
        return $this->hasOne(User::class, 'id', 'from');
    }
}