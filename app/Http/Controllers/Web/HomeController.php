<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Account\AddRoomRequest;
use App\Http\Requests\Web\Account\SendMessRequest;
use App\Models\User;
use App\Services\MessageService;
use App\Services\RoomService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $roomService;
    protected $userService;

    protected $messageService;

    public function __construct(RoomService $roomService, UserService $userService, MessageService $messageService){
        $this->roomService = $roomService;
        $this->userService = $userService;
        $this->messageService = $messageService;
    }

    public function landingPage(){
        return view('home.landingpage');
    }



    public function getUsersWithRolesInRoom($room_id)
    {
        // Truy vấn danh sách người dùng cùng quyền trong phòng hiện tại
        $usersWithRoles = \DB::table('room_roles')
            ->join('users', 'room_roles.user_id', '=', 'users.user_id')
            ->where('room_roles.room_id', $room_id)
            ->select('users.*', 'room_roles.room_id', 'room_roles.role_id')
            ->get();

        return $usersWithRoles;
    }

    public function chat($room_id)
    {
        $user_id = Auth::id();
        // dd($user_id);
        $listUsers = $this->userService->getList();
        // Lấy danh sách các phòng mà người dùng tham gia
        $rooms = $this->roomService->getDefaultRoom($user_id);

        // $firstRoom = $rooms->first();
        
       
        // Kiểm tra xem phòng có tồn tại không
        if ($room_id) {
            // Lấy danh sách thành viên và quyền trong phòng
            $userInRooms = $this->getUsersWithRolesInRoom($room_id);
            // Sắp xếp theo quyền trong phòng chat
            $userInRooms = $userInRooms->sortBy('role_id');
            
            $messages = $this->getMessagesInRoom($room_id);

            // Duyệt qua tin nhắn và kết hợp thông tin người gửi với tin nhắn
            foreach ($messages as $message) {
                $userSendMess = $userInRooms->where('user_id', $message->user_id)->first();
                if ($userSendMess) {
                    $message->username = $userSendMess->username; 
                    $message->img_path = $userSendMess->img_path; 
                }
            }

            return view('home.chat', [
                'room_id' => $room_id,
                'listUsers'=> $listUsers, 
                'rooms' => $rooms, 
                'userInRooms' => $userInRooms, 
                'messages' => $messages
            ]);
        }

        // Nếu không có phòng, trả về trang chat mà không có tin nhắn
        return view('home.chat', ['rooms' => $rooms, 'messages' => []]);
    }


    public function getMessagesInRoom($roomId)
    {
        $userId = Auth::id(); 

        // Truy vấn tất cả tin nhắn trong phòng chat
        $messages = $this->messageService->getMessages($roomId);

        // Phân biệt tin nhắn của người dùng hiện tại và các thành viên khác
        foreach ($messages as $message) {
            if ($message->user_id == $userId) {
                $message->is_current_user = true; // Tin nhắn của người dùng hiện tại
            } else {
                $message->is_current_user = false; // Tin nhắn của các thành viên khác
            }
        }

        return $messages;
    }

    public function addRoom(AddRoomRequest $addRoomRequest){
        $request = $addRoomRequest->validated();

        $room = $this->roomService->addRoom($request);

        if($room){
            return redirect()->route(Auth::user()->role_id==1 ? 'admin.home.chat' : 'spacebox.home.chat', $room->room_id)->with('chat-success', 'Thêm phòng chat thành công');
        }

        return redirect()->back()->with('error', 'Thêm phòng chat không thành công');

    }


   


    public function sendMessage(SendMessRequest $sendMessRequest){
        $request = $sendMessRequest->validated();

        $message = $this->messageService->createMessage($request);

        if($message){
            return redirect()->back();
        }
    }

    


   
}
