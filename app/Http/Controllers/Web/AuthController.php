<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function registerForm(){
        return view('auth.register');
    }
    public function loginForm(){
        return view('auth.login');
    }
}
