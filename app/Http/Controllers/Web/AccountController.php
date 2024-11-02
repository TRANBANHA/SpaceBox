<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Account\RegisterRequest;
use App\Mail\VerifyAccount;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Mail;

class AccountController extends Controller
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
        $acc = $this->userService->create_user($request);

        if ($acc) {
            Mail::to($request['email'])->send(new VerifyAccount($acc));

            return view('auth.login')->with('message', 'Vui lòng kiểm tra email để xác thực tài khoản.');
        }

        return response()->json(['message' => 'Đăng ký tài khoản không thành công'], 500);
    }


    public function verifyEmail($email){


        $updated = User::where('email', $email)
                   ->whereNull('email_verified_at')
                   ->update(['email_verified_at' => now()]);

        if ($updated) {
            
            return redirect()->route('account.login')->with("message", "Email đã được xác thực thành công.");
        
        } else {

            abort(404, 'Not Found');
        }
        
    
    }
}
