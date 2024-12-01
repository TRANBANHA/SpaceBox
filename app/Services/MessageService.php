<?php

namespace App\Services;
use App\Models\Message;
use Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
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



    public function createFileMessage($param)
    {
        $mess = [
            'user_id' => $param['user_id'],
            'room_id' => $param['room_id'],
            'content' => $param['content'],
            'file_path' => $param['file_mess'],
            'is_current_user' => 1,
        ];

        

        return $this->message->create($mess);
    }


    public function createMessage($param)
    {
        $mess = [
            'user_id' => $param['user_id'],
            'room_id' => $param['room_id'],
            'content' => $param['content'],
            'is_current_user' => 1,
        ];

        return $this->message->create($mess);
    }

    
    public function pinnedMessage($messageId)
    {
        Message::where('message_id', $messageId)->update(['is_pinned' => 1]);

        return true;
    }
    public function unpinnedMessage($messageId)
    {
        Message::where('message_id', $messageId)->update(['is_pinned' => 0]);

        return true;
    }
    public function deleteMessage($messageId)
    {
        Message::where('message_id', $messageId)->update(['is_deleted' => 1, 'content' => 'Tin nhắn đã được thu hồi', 'file_path' => null]);

        return true;
    }

}