<?php


namespace App\Services;
use App\Models\Room;
use App\Models\RoomRole;
use Auth;


class RoomService
{
    
    protected $room;
    protected $roomRole;

    
    public function __construct(Room $room, RoomRole $roomRole)   
    {
        $this->room = $room;
        $this->roomRole = $roomRole;
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

        $newRoom = $this->room->create($room);

        if (isset($param['members']) && count($param['members']) > 0) {
            
            $newRoom->userId = $param['members'];

            $this->addRoomRoleByUser($newRoom);
        }else{
            $this->addRoomRoleByUser($newRoom);
        }
        return $newRoom;
    }


    //RoomRoleUser

    public function addRoomRoleByUser($room)
    {

        // Nếu có nhiều user, tạo role cho từng người dùng
        if ($room['userId'] > 1) {

            $room['userId'] = array_merge([$room['created_by']], $room['userId']);

            foreach ($room['userId'] as $user_id) {
                // Xác định role của người dùng
                $role = $user_id == Auth::user()->user_id ? 2 : 3; // Nếu là người tạo phòng (admin), gán role = 2 (admin)
                
                // Tạo room role cho từng user
                $roomRole = [
                    'room_id' => $room['room_id'],
                    'role_id' => $role,
                    'user_id' => $user_id,
                ];
                
                // Lưu vào cơ sở dữ liệu
                $this->roomRole->create($roomRole);
            }
        } else {
            // Nếu chỉ có một người, gán vai trò admin (role = 2)
            $roomRole = [
                'room_id' => $room['room_id'],
                'role_id' => 2,  
                'user_id' => $room['created_by'], 
            ];
            
            // Lưu vào cơ sở dữ liệu
            $this->roomRole->create($roomRole);
        }

    }



    public function getListRoomUser($user_id)
    {
        // Lấy danh sách room_id mà user tham gia
        $roomIds = $this->roomRole->where('user_id', $user_id)->pluck('room_id');

        // Lấy danh sách các phòng và sắp xếp theo thời gian tin nhắn mới nhất hoặc ngày tạo phòng nếu không có tin nhắn
        $rooms = $this->room->whereIn('rooms.room_id', $roomIds)
            ->leftJoin('messages', 'rooms.room_id', '=', 'messages.room_id')
            ->select('rooms.*', \DB::raw('COALESCE(MAX(messages.created_at), rooms.created_at) as latest_message_time')) // Nếu không có tin nhắn, dùng thời gian tạo phòng
            ->groupBy('rooms.room_id')
            ->orderByDesc('latest_message_time') // Sắp xếp theo tin nhắn mới nhất hoặc thời gian tạo phòng
            ->get();

        return $rooms;
    }

    public function getDefaultRoom($user_id)
    {
        // Lấy danh sách các phòng mà user tham gia
        $rooms = $this->getListRoomUser($user_id);

        // Nếu không có phòng nào, trả về null
        // if ($rooms->isEmpty()) {
        //     return null;
        // }
        if($rooms == null){
            $rooms = new Room();
            $rooms->room_id = 0;
        }

        return $rooms; // Trả về danh sách các phòng đã được sắp xếp
    }

    public function getUsersInRoom($roomId)
    {
        // Lấy user dựa trên room_id
        $idUserInRoom = $this->roomRole->where('room_id', $roomId)->select('user_id', 'role_id')->get();

        return $idUserInRoom;
    }

    public function getRoleInRoom($room_id, $user_id)
    {
        // Lấy tất cả các role_id của user trong phòng
        $rolesInRoom = $this->roomRole->where('room_id', $room_id)
                                      ->where('user_id', $user_id)
                                      ->pluck('role_id');   // Lấy tất cả các role_id
    
        return $rolesInRoom;
    }
    

}