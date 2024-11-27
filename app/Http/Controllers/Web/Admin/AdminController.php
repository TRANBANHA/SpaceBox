<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Account\ChangePasswordRequest;
use App\Http\Requests\Web\Admin\AddRoomRequest;
use App\Http\Requests\Web\Admin\AddUserRequest;
use App\Http\Requests\Web\Admin\DeleteRoomRequest;
use App\Http\Requests\Web\Admin\UpdateProfileRequest;
use App\Http\Requests\Web\Admin\DeleteRequest;
use App\Http\Requests\Web\Admin\DeleteUserRequest;
use App\Http\Requests\Web\Admin\UpdateRoomRequest;
use App\Models\Room;
use App\Models\User;
use App\Services\RoleService;
use App\Services\RoomService;
use App\Services\UserService;
use Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Hash;

class AdminController extends Controller
{
    protected $roleService;
    protected $userService;
    protected $roomService;
    public function __construct(RoleService $roleService, UserService $userService, RoomService $roomService,){
        $this->roleService = $roleService;
        $this->userService = $userService;
        $this->roomService = $roomService;
    }

    public function getFirstRoom(){
        $room = $this->roomService->getDefaultRoom(Auth::user()->user_id)->first();
        if($room == null){
            $room = new Room();
            $room->room_id = 0;
        }
        return $room;
    }

    public function index(){
        $list = $this->userService->getList();
        $room = $this->getFirstRoom();
        return view('admin.user-managers', ['users' => $list, 'room_id' => $room->room_id]);
    }

    public function getListUser(){
        $list = $this->userService->getList();
        $room = $this->getFirstRoom();
        return view('admin.user-managers', ['users' => $list, 'room_id' => $room->room_id]);
    }
    

    public function getProfile(){
        $room = $this->getFirstRoom();
        if(Auth::user()->role_id == 1){
            $admin = $this->userService->getUserId(Auth::user()->user_id);
            return view('admin.profile', ['admin' => $admin, 'room_id' => $room->room_id]);
        }
    }

    


    public function changePassForm(){
        $room = $this->getFirstRoom();
        return view('admin.change-pass', ['room_id' => $room->room_id]);    
    } 

    public function changePassAction(ChangePasswordRequest $changePasswordRequest){
        $request = $changePasswordRequest->validated();

        if(Auth::check()){
            $user = $this->userService->getUserId(Auth::user()->user_id);
            if ($user) {
                $user->password = Hash::make($request['password']);
                $user->save();

                return redirect()->back()->with('success','Đổi mật khẩu thành công');
            }
        }
        else{
            return redirect()->route('account.login');
        }
    }


    // Quản lý người dùng


    public function deleteUser(DeleteUserRequest $deleteUserRequest)
    {
        $request = $deleteUserRequest->validated();
        // Lấy chuỗi user_ids từ mảng
        $userIdsString = $request['user_ids'][0]; 
        // Chuyển chuỗi user_ids thành mảng
        $userIds = explode(',', $userIdsString);
        User::whereIn('user_id', $userIds)->delete();
        // Nếu muốn xóa vĩnh viễn (bỏ qua SoftDeletes)
        // User::whereIn('id', $request['user_ids'])->forceDelete();

        
        return redirect()->back()->with('admin-success', 'Xoá người dùng thành công.');
    }


    public function updateUserForm($user_id){
        $user = $this->userService->getUserId($user_id);
        $roles = $this->roleService->getRole();
        $room = $this->getFirstRoom();

        return view('admin.updateUsers', ['user' => $user, 'roles' => $roles, 'room_id' => $room->room_id]);
    }
    
    public function updateProfile(UpdateProfileRequest $updateProfileRequest)
    {
        $request = $updateProfileRequest->validated();
        
        $admin = auth()->user();

        $admin->username = $request['username'];
        $admin->gender = $request['gender'];
        $admin->description = $request['description'];
        $admin->role_id = 1;

        if($updateProfileRequest->hasFile('fileImg')){
            if ($admin->img_path) {
                Cloudinary::destroy($admin->img_path);
            }


            $imgUploadFile = Cloudinary::upload($updateProfileRequest->file('fileImg')->getRealPath())->getSecurePath();


            if ($imgUploadFile) {
                $admin->img_path = $imgUploadFile;
            } else {
                return redirect()->back()->with('error', 'Tải ảnh không thành công');
            }
        }
        $admin->save();
        return redirect()->back()->with('success', 'Cập nhật thông tin thành công');

    }
    public function updateProfileUser(UpdateProfileRequest $updateProfileRequest, $user_id)
    {
        $request = $updateProfileRequest->validated();
        
        $user = $this->userService->getUserId($user_id);

        $user->username = $request['username'];
        $user->gender = $request['gender'];
        $user->description = $request['description'];
        $user->role_id = $request['role_id'];

        if($updateProfileRequest->hasFile('fileImg')){
            if ($user->img_path) {
                Cloudinary::destroy($user->img_path);
            }

            $imgUploadFile = Cloudinary::upload($updateProfileRequest->file('fileImg')->getRealPath())->getSecurePath();


            if ($imgUploadFile) {
                $user->img_path = $imgUploadFile;
            } else {
                return redirect()->back()->with('error', 'Tải ảnh không thành công');
            }
        }
        $user->save();

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công');

    }


    public function lockAccountUser($user_id){
        $user = $this->userService->getUserId($user_id);
        $user->status = 0;
        $user->save();
        return redirect()->back()->with('admin-success', 'Khóa tài khoản thành công');
    }


    public function unlockAccountUser($user_id){
        $user = $this->userService->getUserId($user_id);
        $user->status = 1;
        $user->save();
        return redirect()->back()->with('admin-success', 'Mở khóa tài khoản thành công');
    }


    public function addUserForm(){
        $roles = $this->roleService->getRole();
        $room = $this->getFirstRoom();
        return view('admin.addUser', ['roles' => $roles, 'room_id' => $room->room_id]);
    }

    public function addUserAction(AddUserRequest $addUserRequest){
        $request = $addUserRequest->validated();
        $user = new User();
        $user->username = $request['username'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
        $user->role_id = $request['role_id'];
        $user->email_verified_at = now();
        $user->status = 1;
        $user->save();
        return redirect()->route('admin.getListUser')->with('admin-success', 'Thêm người dùng thành công');
    }


    // Quản lý room chat

    public function getListRoom(){
        $rooms = $this->roomService->getList();
        $firstRoom = $this->getFirstRoom();
        return view('admin.room-managers', ['rooms' => $rooms,  'room_id' => $firstRoom->room_id]);
    }

    public function deleteRoom(DeleteRoomRequest $deleteRoomRequest){
        $request = $deleteRoomRequest->validated();

        $roomIdsString = $request['room_ids'][0];

        $roomIds = explode(',', $roomIdsString);

        Room::whereIn('room_id', $roomIds)->delete();

        return redirect()->back()->with('admin-success', 'Xoá phòng chat thành công.');
    }


    public function addRoom(AddRoomRequest $addRoomRequest){
        $request = $addRoomRequest->validated();

        $room = $this->roomService->addRoom($request);

        // $roomRoleUser = $this->roomService->addRoomRoleByUser($room);

        if($room){
            return redirect()->route('admin.getListRoom')->with('admin-success', 'Thêm phòng chat thành công');
        }

        return redirect()->back()->with('error', 'Thêm phòng chat không thành công');

    }


    public function updateRoom(UpdateRoomRequest $updateRoomRequest, $room_id){
        $request = $updateRoomRequest->validated();

        $room = $this->roomService->getRoomId($room_id);

        $room->room_name = $request['room_name'];

        $room->save();

        return redirect()->back()->with('admin-success', 'Cập nhật phòng chat thành công');

    }
}
