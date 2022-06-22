<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Traits\ApiTrait;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, ApiTrait;

    protected $table = "users";

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['remember_token', 'password'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Relación uno a muchos
    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function accessToken(){
        return $this->hasOne(AccessToken::class);
    }
}
