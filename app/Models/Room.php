<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $primaryKey = 'room_id';
    protected $fillable = [
        'room_name',
        'avt_path',
        'created_by',
        
    ];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
