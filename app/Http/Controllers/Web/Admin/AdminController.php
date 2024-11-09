<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

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
}
