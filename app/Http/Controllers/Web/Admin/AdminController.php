<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Account\ChangePasswordRequest;
use App\Http\Requests\Web\Account\UpdateProfileRequest;
use App\Services\UserService;
use Auth;
use Hash;

class AdminController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService){
        $this->userService = $userService;
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

    
}
