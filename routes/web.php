<?php

use App\Http\Controllers\Web\AccountController;
use App\Http\Controllers\Web\Admin\AdminController;
use App\Http\Controllers\Web\HomeController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'account', 'as' => 'account.'], function(){
    Route::get('/register', [AccountController::class, 'registerForm'])->name('register');
    Route::post('/register',[AccountController::class, 'registerAction'])->name('register.auth');

    Route::get('/verify/{email}',[AccountController::class, 'verifyEmail'])->name('verify');

    Route::get('/login', [AccountController::class, 'loginForm'])->name('login');
    Route::post('/login',[AccountController::class, 'loginAction'])->name('login.auth');

    Route::get('/logout', [AccountController::class, 'logoutAction'])->name('logout');
});

    

Route::group(['prefix' => 'admin', 'middleware' => 'check_admin' ,'as' => 'admin.'], function(){
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/quan-ly-nguoi-dung', [AdminController::class, 'getListUser'])->name('getListUser');
    Route::get('/spacebox', [HomeController::class, 'index'])->name('home.index');
});
Route::group(['prefix' => 'spacebox', 'middleware' => 'check_user' ,'as' => 'spacebox.'], function(){

    Route::get('/', [HomeController::class, 'index'])->name('home.index');
});


