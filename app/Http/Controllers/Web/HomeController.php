<?php

namespace App\Http\Controllers\Web;

use App\Events\AddUserRoom;
use App\Events\ChatEvent;
use App\Events\IndexRoomEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Account\AddRoomRequest;
use App\Http\Requests\Web\Account\SendFileRequest;
use App\Http\Requests\Web\Account\SendMessRequest;
use App\Http\Requests\Web\Account\UpdateRoomUserRequest;
use App\Models\Room;
use App\Models\User;
use App\Services\MessageService;
use App\Services\RoomService;
use App\Services\UserService;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
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
        $user = Auth::user();



        $listUsers = $this->userService->getList();
        // Lấy danh sách các phòng mà người dùng tham gia
        $rooms = $this->roomService->getDefaultRoom($user->user_id);
        //Lấy phòng có tin nhắn mới nhất
        $roomFirst = $room_id ? $this->roomService->getRoomId($room_id) : $rooms->first();
        

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
                'user' => $user,
                'roomFirst' => $roomFirst,
                'room_id' => $room_id,
                'listUsers'=> $listUsers, 
                'rooms' => $rooms, 
                'userInRooms' => $userInRooms, 
                'messages' => $messages
            ]);
        }

        // Nếu không có phòng, trả về trang chat mà không có tin nhắn
        return view('home.chat', [
            'user' => $user,
            'roomFirst' => $roomFirst,
            'room_id' => $room_id,
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

        $roomAdd = Room::where('room_id', $room->room_id)->first();

        if($roomAdd){
            $userInRoom = $this->roomService->getUsersInRoom($roomAdd->room_id)->toArray();
            
            $roomData = [
                'room_id' => $roomAdd->room_id,
                'room_name' => $roomAdd->room_name,
                'latestMess' => '...',
                'latestMessTime' => $roomAdd->created_at->format('H:i'),
                'avt_path' => $roomAdd->avt_path,
                'userInRoom' => $userInRoom
            ];

            broadcast(new AddUserRoom($roomData));
            
            return redirect()->route(Auth::user()->role_id==1 ? 'admin.home.chat' : 'spacebox.home.chat', $room->room_id)->with('chat-success', 'Thêm phòng chat thành công');
        }

        return redirect()->back()->with('chat-error', 'Thêm phòng chat không thành công');

    }

    public function eventSendMess($message){
        $userSendMess = User::find($message->user_id);
        if ($userSendMess) {
            $message->username = $userSendMess->username; 
            $message->img_path = $userSendMess->img_path; 
        }

        $room = Room::where('room_id', $message->room_id)->first();
        if($room != null){
            $userInRoom = $this->roomService->getUsersInRoom($room->room_id)->toArray();
        
            $roomData = [
                'room_id' => $room->room_id,
                'room_name' => $room->room_name,
                'latestMess' => $message->content,
                'latestMessTime' => $message->created_at->format('H:i'),
                'avt_path' => $room->avt_path,
                'userInRoom' => $userInRoom
                
            ];
        }
        // dd($roomData);
       
        
         // Chuẩn bị dữ liệu phát qua sự kiện
        $messData = [
            'message_id' => $message->message_id,
            'user_id' => $message->user_id,
            'room_id' => $message->room_id,
            'content' => $message->content,
            'is_deleted' => $message->is_deleted,
            'is_pinned' => $message->is_pinned,
            'created_at' => $message->created_at->format('H:i'),
            'file_path' => $message->file_path,
            'username' => $message->username,
            'img_path' => $message->img_path,
            'is_current_user' => $message->is_current_user,
            'userInRooms' => $userInRoom
        ];

        // dd($messData);


        
        broadcast(new ChatEvent($messData['room_id'],$messData));
       
        broadcast(new IndexRoomEvent($roomData));

        return true;
    }

    public function sendFile(SendFileRequest $sendFileRequest)
    {
        $request = $sendFileRequest->validated();

        if($sendFileRequest->hasFile('file_mess')){

            $file = $sendFileRequest->file('file_mess');

            $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); 
            $originalExtension = $file->getClientOriginalExtension(); 
            $newFileName = $originalFileName . '.' . $originalExtension; 

            $renamedFilePath = $file->move(
                sys_get_temp_dir(), 
                $newFileName     
            );
            
    
                // Xử lý upload raw files cho các loại file như .xls, .doc, .pdf
            $uploadedFile = Cloudinary::upload($renamedFilePath->getRealPath(), [
                'resource_type' => 'auto',
                'public_id' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'use_filename' => true, // Sử dụng tên file gốc
                'unique_filename' => false, 
            ])->getSecurePath();

            if ($uploadedFile) {
                $request['content'] = $file->getClientOriginalName();
                $request['file_mess'] = $uploadedFile;
            } else {
                return redirect()->back()->with('chat-error', 'Tải file không thành công');
            }
        }
        $message = $this->messageService->createFileMessage($request);


        if($message){
            $event = $this->eventSendMess($message);
            if($event){
                return redirect()->back()->with('chat-success', 'Gửi file thành công');
            }
        }
        return redirect()->back()->with('chat-error', 'Gửi file không thành công');
        // dd($message);
    }
    


    public function sendMessage(SendMessRequest $sendMessRequest){
        $request = $sendMessRequest->validated();

        $message = $this->messageService->createMessage($request);
        // dd($message);
        if($message){
            $event = $this->eventSendMess($message);
            if($event){
                return redirect()->back()->with('chat-success', 'Gửi tin nhắn thành công');
            }
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


    public function updateRoom(UpdateRoomUserRequest $updateRoomUserRequest){
        $request = $updateRoomUserRequest->validated();
        $room = $this->roomService->getRoomId($request['room_id']);

       
        $room->room_name = $request['room_name'];

        if($updateRoomUserRequest->hasFile('fileImg_room')){
            if ($room->avt_path) {
                Cloudinary::destroy($room->avt_path);
            }

            $avtUploadFile = Cloudinary::upload($updateRoomUserRequest->file('fileImg_room')->getRealPath())->getSecurePath();


            if ($avtUploadFile) {
                $room->avt_path = $avtUploadFile;
            } else {
                return redirect()->back();
            }
        }

        $room->save();

        return redirect()->back();

    }
}
