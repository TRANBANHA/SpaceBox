<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomRole extends Model
{
    use HasFactory;

    protected $table = 'room_roles';
    protected $primaryKey = 'room_role_id';

    protected $fillable = [
        'room_id',
        'role_id',
        'user_id',        
    ];
}
