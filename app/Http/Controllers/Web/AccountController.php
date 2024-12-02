<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Account\ChangePasswordRequest;
use App\Http\Requests\Web\Account\ForgotPassRequest;
use App\Http\Requests\Web\Account\LoginRequest;
use App\Http\Requests\Web\Account\RegisterRequest;
// use App\Http\Requests\Web\Account\UpdateProfileRequest;
use App\Http\Requests\Web\Account\UpdateProfileRequest;
use App\Http\Requests\Web\Account\UpdateProfileUserRequest;
use App\Mail\ResetPassword;
use App\Mail\VerifyAccount;
use App\Models\User;
use App\Services\RoomService;
use App\Services\UserService;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Hash;
use Mail;
use Illuminate\Support\Facades\Auth;
use Str;

class AccountController extends Controller
{
    protected $userService;
    protected $roomService;

    public function __construct(UserService $userService, RoomService $roomService){
        $this->userService = $userService;
        $this->roomService = $roomService;
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

        $acc = $this->userService->register_user($request);

        if ($acc) {
            Mail::to($request['email'])->send(new VerifyAccount($acc));

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
    
            $room = $this->roomService->getDefaultRoom(Auth::user()->user_id)->first();
            // Phân quyền dựa trên role_id
            switch (Auth::user()->role_id) {
                case 1: // Admin
                    
                    return redirect()->route('admin.index');
                default: // Các quyền khác
                   

                    // return redirect()->route('spacebox.home.chat', $room->room_id);
                    if($room){
                        return redirect()->route('spacebox.home.chat', $room->room_id);
                    } else {
                        return redirect()->route('spacebox.home.chat', 0);
                    }
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


    
    
    // Update profile
    public function updateProfile(UpdateProfileUserRequest $updateProfileUserRequest)
    {
        $request = $updateProfileUserRequest->validated();
        
        $user = auth()->user();

        $user->username = $request['username'];
        $user->gender = $request['gender'];
        $user->description = $request['description'];

        if($updateProfileUserRequest->hasFile('fileImg')){
            if ($user->img_path) {
                Cloudinary::destroy($user->img_path);
            }


            $imgUploadFile = Cloudinary::upload($updateProfileUserRequest->file('fileImg')->getRealPath())->getSecurePath();


            if ($imgUploadFile) {
                $user->img_path = $imgUploadFile;
            } else {
                return redirect()->back()->with('error', 'Tải ảnh không thành công');
            }
        }
        $user->save();
        return redirect()->back()->with('update-success', 'Cập nhật thông tin thành công');

    }

    public function updatePass(ChangePasswordRequest $changePasswordRequest) {
        $request = $changePasswordRequest->validated();
        $user = auth()->user();

        try {
            $user->password = Hash::make($request['password']);
            $user->save();
    
            return redirect()->back()->with('success', 'Đổi mật khẩu thành công.');
        } catch (\Exception $e) {
            return redirect()->back();
            // return redirect()->back()->withErrors(['update_password_error' => 'Có lỗi xảy ra khi đổi mật khẩu. Vui lòng thử lại.']);
        }
    }


    public function forgotPassForm(){
        return view('auth.forgot-pass');
    }

    // Gửi email reset password
    public function sendEmailResetPass(ForgotPassRequest $forgotPassRequest){
        $request = $forgotPassRequest->validated();

        $user = User::where('email', $request['email'])->first();

        if($user){
            $newPassword = Str::random(8);
            $user->password = Hash::make($newPassword);

            $user->save();

            Mail::to($request['email'])->send(new ResetPassword($user,$newPassword));

            return redirect()->back()->with('success', [
                'title' => 'Gửi yêu cầu thành công',
                'content' => 'Mật khẩu mới đã được gửi đến email của bạn.'
            ]);
        }
    }



}
