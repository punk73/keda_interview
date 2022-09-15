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
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class User extends Model implements Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, Auth, SoftDeletes;

    protected $hidden = ['password'];
    protected $guarded = ['id'];

    public function conversations() {
        // get messages with latest message per recipient id

        return Message::whereIn('id', function ($query) {
            $query->selectRaw('max(id)')
            ->from('messages')
            ->groupBy(['conversation_id'])
            ->get();
        })->select('sender_id','recipient_id','conversation_id', 'content', 'created_at')
        ->where(function($q) {
            if($this->isCustomer()){
                $q->where('recipient_id', $this->id)
                ->orWhere('sender_id', $this->id);
            }
        })
        ->orderBy('created_at', 'desc')
        ->get();
    }

    public function user_type(){
        return $this->belongsTo(UserType::class);
    }

    public function customers(){
        return $this->with(['user_type:id,name'])->whereHas('user_type', function($q) {
            $q->where('name', 'Customer');
        });
    }
    
    public function staffs(){
        return $this->with(['user_type:id,name'])->whereHas('user_type', function ($q) {
            $q->where('name', 'Staff');
        });
    }

    public function isStaff(){
        $userType = $this->user_type;
        if(!$userType) {
            return false;
        }

        return $userType->name== 'Staff';
    }

    public function isCustomer(){
        // asumsi kalau bukan staff, ya customer;
        return !$this->isStaff();
    }

}
