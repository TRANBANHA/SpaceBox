<?php

use App\Http\Controllers\Web\AccountController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\HomeController;
use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'account'], function(){
    Route::get('/register', [AccountController::class, 'registerForm'])->name('account.register');
    Route::post('/register',[AccountController::class, 'registerAction'])->name('account.register.auth');
    Route::get('/verify/{email}',[AccountController::class, 'verifyEmail'])->name('account.verify');


    Route::get('/login', [AccountController::class, 'loginForm'])->name('account.login');
    Route::post('/login',[AccountController::class, 'loginAction'])->name('account.login.auth');
});

Route::group(['prefix' => 'spacebox'], function(){
    
    // Route::post('/sendmail/{email}', [AccountController::class, 'sendMail'])->name('account.sendMail');
    Route::get('/', [HomeController::class, 'index'])->name('spacebox.home.index');
    Route::get('/admin', [AdminController::class, 'index'])->name('spacebox.admin.index');
});
