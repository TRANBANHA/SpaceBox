<?php

namespace App\Services;
use App\Models\Message;
use Auth;
class MessageService
{
    protected $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function getMessages($roomId)
    {
        return $this->message->where('room_id', $roomId)->orderBy('created_at', 'asc')->get();
    }

    public function createMessage($param)
    {
        $mess = [
            'user_id' => Auth::user()->user_id,
            'room_id' => $param['room_id'],
            'content' => $param['content'],
            'is_current_user' => 1,
        ];

        return $this->message->create($mess);
    }
    
}