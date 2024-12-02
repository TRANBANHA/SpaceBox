<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // use SoftDeletes;
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'google_id',
        'email',
        'password',
        'username',
        'img_path',
        'gender',
        'description',
        'role_id',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function rooms()
    {
        return $this->belongsToMany(Room::class);
    }

    
}
