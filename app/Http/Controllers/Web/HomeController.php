<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{

    public function landingPage(){
        return view('home.landingpage');
    }

    public function index(){

        return view('home.index');
    }
}
