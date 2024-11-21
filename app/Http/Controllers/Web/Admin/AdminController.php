<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Account\ChangePasswordRequest;
use App\Http\Requests\Web\Admin\AddUserRequest;
use App\Http\Requests\Web\Admin\UpdateProfileRequest;
use App\Http\Requests\Web\Admin\DeleteRequest;
use App\Http\Requests\Web\Admin\DeleteUserRequest;
use App\Models\User;
use App\Services\RoleService;
use App\Services\UserService;
use Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Hash;

class AdminController extends Controller
{
    protected $userService;
    protected $roleService;
    public function __construct(UserService $userService, RoleService $roleService){
        $this->userService = $userService;
        $this->roleService = $roleService;
    }
    public function index(){
        $list = $this->userService->getList();
        return view('admin.user-managers', ['users' => $list]);
    }

    public function getListUser(){

        $list = $this->userService->getList();
        return view('admin.user-managers', ['users' => $list]);
    }
    public function getListRoom(){

        return view('admin.room-managers');
    }

    public function getProfile(){
        if(Auth::user()->role_id == 1){
            $admin = $this->userService->getUserId(Auth::user()->user_id);
            return view('admin.profile', ['admin' => $admin]);
        }
    }

    


    public function changePassForm(){
        return view('admin.change-pass');
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

        
        return redirect()->back()->with('deleteSuccess', 'Xoá người dùng thành công.');
    }


    public function updateUserForm($user_id){
        $user = $this->userService->getUserId($user_id);
        $roles = $this->roleService->getRole();
        return view('admin.updateUsers', ['user' => $user, 'roles' => $roles]);
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
        return redirect()->back()->with('success', 'Khóa tài khoản thành công');
    }


    public function unlockAccountUser($user_id){
        $user = $this->userService->getUserId($user_id);
        $user->status = 1;
        $user->save();
        return redirect()->back()->with('success', 'Mở khóa tài khoản thành công');
    }


    public function addUserForm(){
        $roles = $this->roleService->getRole();
        return view('admin.addUser', ['roles' => $roles]);
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
        return redirect()->route('admin.getListUser')->with('success', 'Thêm người dùng thành công');
    }
}
