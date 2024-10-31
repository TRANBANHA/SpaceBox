<?php

use App\Http\Controllers\Web\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('home.index');
});


Route::get('/register', [AuthController::class, 'registerForm'])->name('auth.register');
Route::get('/login', [AuthController::class, 'loginForm'])->name('auth.login');