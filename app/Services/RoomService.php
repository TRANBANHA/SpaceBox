<?php


namespace App\Services;
use App\Models\Room;
use Auth;


class RoomService
{
    
    protected $room;

    public function __construct(Room $room)
    {
        $this->room = $room;
    }
    public function getList()
    {
        return $this->room->all();
    }

    public function getRoomId($id)
    {
        return $this->room->where('room_id', $id)->first();
    }

    public function addRoom($param)
    {
        $room = [
            'room_name' => $param['room_name'],
            'created_by' => Auth::user()->user_id,
        ];

        return $this->room->create($room);
    }

   
}