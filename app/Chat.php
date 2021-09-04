<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Events\BroadcastChat;
use Illuminate\Database\Eloquent\SoftDeletes;
class Chat extends Model
{
    use SoftDeletes; //Implementamos 
    protected $dispatchesEvents = [
        'created' => BroadcastChat::class
    ];
    protected $fillable = ['user_id', 'friend_id', 'chat'];
}