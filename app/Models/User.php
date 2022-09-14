<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Authenticatable as Auth;
use Illuminate\Contracts\Auth\Authenticatable;
class User extends Model implements Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, Auth;

    protected $hidden = ['password'];
    protected $guarded = ['id'];
}
