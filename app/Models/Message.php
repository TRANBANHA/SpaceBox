<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $primaryKey = 'message_id';
    protected $fillable = [
        'user_id',
        'room_id',
        'content',
        'file_path',
        'is_pinned',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
