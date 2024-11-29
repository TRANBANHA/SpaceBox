<?php

namespace App\Http\Controllers\Web;

use App\Events\AddUserRoom;
use App\Events\ChatEvent;
use App\Events\IndexRoomEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Account\AddRoomRequest;
use App\Http\Requests\Web\Account\SendMessRequest;
use App\Models\Room;
use App\Models\User;
use App\Services\MessageService;
use App\Services\RoomService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

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
        if(Auth::user()->status == 0){
            Auth::logout();
            return redirect()->route('account.login')->with('errors', [
                'title' => 'Đăng nhập không thành công',
                'content' => 'Tài khoản của bạn đã bị khoá hoặc vô hiệu hoá'
            ]);
        }
        $user_id = Auth::id();

        $listUsers = $this->userService->getList();
        // Lấy danh sách các phòng mà người dùng tham gia
        $rooms = $this->roomService->getDefaultRoom($user_id);

        if($rooms != null){
            foreach ($rooms as $room) {
                $messages = $this->getMessagesInRoom($room->room_id);
                $latestMess = $messages->where('room_id', $room->room_id)->sortByDesc('created_at')->first();
                if($latestMess){
                    $room->latestMess = $latestMess->content;
                    $room->latestMessTime = $latestMess->created_at;
                }else{
                    $room->latestMess = '...';
                    $room->latestMessTime = $room->created_at;
                }
                
            }
        }

        // Kiểm tra xem id phòng có tồn tại không
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
        return view('home.chat', [
            'room_id' => 0,
            'listUsers'=> $listUsers, 
            'rooms' => $rooms, 
            'userInRooms' => [], 
            'messages' => []
        ]);
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
            $roomData = [
                'room_id' => $room->room_id,
                'room_name' => $room->room_name,
                'latestMess' => '...',
                'latestMessTime' => $room->created_at->format('H:i'),
                'avt_path' => $room->avt_path,
            ];
            broadcast(new AddUserRoom($roomData));
            
            return redirect()->route(Auth::user()->role_id==1 ? 'admin.home.chat' : 'spacebox.home.chat', $room->room_id)->with('chat-success', 'Thêm phòng chat thành công');
        }

        return redirect()->back()->with('error', 'Thêm phòng chat không thành công');

    }



    public function sendMessage(SendMessRequest $sendMessRequest){
        $request = $sendMessRequest->validated();



        $message = $this->messageService->createMessage($request);
        // dd($message);
        if($message){
            $userSendMess = User::find($message->user_id);
            if ($userSendMess) {
                $message->username = $userSendMess->username; 
                $message->img_path = $userSendMess->img_path; 
            }

            // $rooms = $this->roomService->getDefaultRoom($message->user_id);
            $room = Room::where('room_id', $message->room_id)->first();
            // $userInRooms = $this->getUsersWithRolesInRoom($room_id);
            // dd($room);
            if($room != null){
                $roomData = [
                    'room_id' => $room->room_id,
                    'room_name' => $room->room_name,
                    'latestMess' => $message->content,
                    'latestMessTime' => $message->created_at->format('H:i'),
                    'avt_path' => $room->avt_path,
                    
                ];
            }
            // dd($roomData);
           
            
             // Chuẩn bị dữ liệu phát qua sự kiện
            $messData = [
                'user_id' => $message->user_id,
                'room_id' => $message->room_id,
                'content' => $message->content,
                'is_deleted' => $message->is_deleted,
                'is_pinned' => $message->is_pinned,
                'created_at' => $message->created_at->format('H:i'),
                'username' => $message->username,
                'img_path' => $message->img_path,
                'is_current_user' => $message->is_current_user,
            ];


            
            broadcast(new ChatEvent($messData['room_id'],$messData))->toOthers();
           
            broadcast(new IndexRoomEvent($roomData));

            // broadcast(new IndexRoomEvent($messData['room_id'],$messData));


            return redirect()->back();
        }
    }

    public function pinnedMessage($messageId){

        $is_pinned = $this->messageService->pinnedMessage($messageId);
       
        if ($is_pinned) {
            return redirect()->back()->with('chat-success', 'Đã ghim tin nhắn');
        }

        return redirect()->back()->with('chat-error', 'Không thể ghim tin nhắn');
    }
    public function unpinnedMessage($messageId){

        $un_pinned = $this->messageService->unpinnedMessage($messageId);
       
        if ($un_pinned) {
            return redirect()->back()->with('chat-success', 'Đã bỏ ghim tin nhắn');
        }

        return redirect()->back()->with('chat-error', 'Không thể bỏ ghim tin nhắn');
    }
    

    public function deleteMessage($messageId){

        $deleteMess = $this->messageService->deleteMessage($messageId);
       
        if ($deleteMess) {
            return redirect()->back()->with('chat-success', 'Tin nhắn đã được thu hồi');
        }

        return redirect()->back()->with('chat-error', 'Không thể thu hồi tin nhắn đã được thu hồi');
    }


   
}
