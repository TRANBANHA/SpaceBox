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
            'password' => Hash::make($param['password'])
        ];

        return $this->user->create($user);
        
    }

    public function getList(){
        return $this->user->all();
    }
}


