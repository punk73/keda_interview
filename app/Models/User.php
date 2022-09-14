<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Authenticatable as Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\UserType;
class User extends Model implements Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, Auth;

    protected $hidden = ['password'];
    protected $guarded = ['id'];

    public function conversations() {
        // get messages with latest message per recipient id

        // return $this->hasMany( Message::class, 'sender_id', 'id')
        // return $this->select([
        //     'content',
        //     'recipient_id',
        //     'messages.created_at',
        //     'messages.updated_at'
        // ])
        //     ->join('messages', 'sender_id', '=', 'users.id')
        //     // ->groupBy(['recipient_id','content'])
        //     ->where('sender_id', $this->id )
        //     ->orWhere('recipient_id', $this->id )
        //     ->get()

        return Message::whereIn('id', function ($query) {
            $query->selectRaw('max(id)')
            ->from('messages')
            ->where( function($q){
                $q->where('recipient_id', '=', $this->id)
                ->orWhere('sender_id', '=', $this->id);
            })
            ->groupBy('sender_id')
            ->get();
        })->select('sender_id', 'content', 'created_at')
        ->orderBy('created_at', 'desc')
        ->get();
    }

    public function user_type(){
        return $this->belongsTo(UserType::class);
    }

}
