<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\User\RegisterRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    public function registerForm(){
        return view('auth.register');
    }
    public function loginForm(){
        return view('auth.login');
    }


    public function registerAction(RegisterRequest $registerRequest)
    {
        $request = $registerRequest->validated();
        $result = $this->userService->create_user($request);

        if ($result) {
            
            return redirect()->route('home.index')->with('success', 'Đăng ký tài khoản thành công.');
        }

        return response()->json(['message' => 'Error creating user'], 500);
    }
}
