<?php
namespace App\Services;


use App\Models\User;
use Hash;
use Log;

class UserService
{
    protected $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    
    public function register_user($param){

        $user = [
            'username' => $param['username'],
            'email' => $param['email'],
            'password' => Hash::make($param['password']),
            'img_path' => 'https://res.cloudinary.com/dy6y1gpgm/image/upload/v1731680383/male_q2q91r.png',
        ];

        return $this->user->create($user);
        
    }

    public function getList(){
        return $this->user->where('role_id', '!=', '1')->get();
    }


    public function getUserId($id){
        return User::where('user_id', $id)->first();
    }
}


