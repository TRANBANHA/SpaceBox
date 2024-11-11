<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Account\LoginRequest;
use App\Http\Requests\Web\Account\RegisterRequest;
use App\Mail\VerifyAccount;
use App\Models\User;
use App\Services\UserService;
use Mail;
use Illuminate\Support\Facades\Auth;

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
        $credentials = $registerRequest->validated();   

        $acc = $this->userService->register_user($credentials);

        if ($acc) {
            Mail::to($credentials['email'])->send(new VerifyAccount($acc));

            return redirect()->back()->with('success', [
                'title' => 'Đăng ký tài khoản thành công',
                'content' => 'Để tiến hành đăng nhập bạn vui lòng kiểm tra Email và xác thực tài khoản'
            ]);
            
        }

        return response()->json(['message' => 'Đăng ký tài khoản không thành công'], 500);
    }


    public function verifyEmail($email){


        $updated = User::where('email', $email)
                   ->whereNull('email_verified_at')
                   ->update(['email_verified_at' => now()]);

        if ($updated) {
            
            return redirect()->route('account.login')->with('success', [
                'title' => 'Xác thực tài khoản thành công',
                'content' => 'Chào mừng bạn đến với cộng đồng của chúng tôi !'
            ]);
        
        } else {

            return redirect()->route('account.login')->with('errors', [
                'title' => 'Xác thực tài khoản không thành công',
                'content' => 'Tài khoản của bạn không tồn tại hoặc đã được xác thực trước đó'
            ]);
        }
    }


    public function loginAction(LoginRequest $loginRequest){
        $request = $loginRequest->validated();
        
        $remember = $loginRequest->has('remember');
        // dd($remember); 
        // Thực hiện xác thực
        if(Auth::attempt(['email' => $request['email'], 'password' => $request['password']], $remember)) {
            // Kiểm tra xem email đã được xác thực chưa
            if(is_null(Auth::user()->email_verified_at)) {
                Auth::logout();
                return redirect()->route('account.login')->with('errors', [
                    'title' => 'Đăng nhập không thành công',
                    'content' => 'Email của bạn chưa được xác thực, vui lòng kiểm tra email để tiến hành xác thực'
                ]);
            }
            elseif(!Auth::user()->status){
                Auth::logout();
                return redirect()->route('account.login')->with('errors', [
                    'title' => 'Đăng nhập không thành công',
                    'content' => 'Tài khoản của bạn đã bị khoá hoặc vô hiệu hoá'
                ]);
            }
    
            // Khởi tạo phiên làm việc
            $loginRequest->session()->regenerate();
    
            // Phân quyền dựa trên role_id
            switch (Auth::user()->role_id) {
                case 1: // Admin
                    return redirect()->route('admin.index');
                default: // Các quyền khác (nếu có)
                    return redirect()->route('spacebox.home.index');
            }
        } else {
            return redirect()->route('account.login')->with('errors', [
                'title' => 'Đăng nhập không thành công',
                'content' => 'Tài khoản hoặc mật khẩu không đúng, vui lòng thử lại'
            ]);
        }
    }
    

    public function logoutAction(){
        Auth::logout();
        
        return redirect()->route('account.login')->with('message','Đăng xuất thành công.');
    }
}
