<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use App\Models\User;
use App\Services\RoomService;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Str;

class GoogleController extends Controller
{

    protected $roomService;
    public function __construct(RoomService $roomService){
        $this->roomService = $roomService;
    }

    public function googleLogin()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleAuthentication()
    {

        try {

            $userGG = Socialite::driver('google')->user();

            $finduser = User::where('email', $userGG->email)->first();

            if ($finduser) {
                Auth::login($finduser);
                if ($finduser->role_id == 1) {
                    return redirect()->route('admin.index');
                }
                $room = $this->roomService->getDefaultRoom($finduser->user_id)->first();
                if($room){
                    return redirect()->route('spacebox.home.chat', $room->room_id);
                }
                return redirect()->route('spacebox.home.chat', 0);
                
                // return redirect()->intended('dashboard');
            } else {
                $newPassword = Str::random(8);
                $newUser = User::updateOrCreate(['email' => $userGG->email], [
                    'google_id' => $userGG->id,
                    'username' => $userGG->name,
                    'password' =>  Hash::make($newPassword),
                    'img_path' => $userGG->avatar,
                    'email_verified_at' => now(),
                    'role_id' => 3,
                ]);


                Mail::to($newUser['email'])->send(new ResetPassword($newUser,$newPassword));

                Auth::login($newUser);
                // Phân quyền dựa trên role_id
                return redirect()->route('spacebox.home.chat', 0);
            }
        } catch (\Exception $e) {
            return redirect()->route('account.auth.google')->with('error', 'Lỗi khi xác thực với Google');
        }
    }
}
