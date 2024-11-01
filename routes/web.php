<?php

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;


// Route::get('/spacebox', function () {
//     return view('home.index');
// });




Route::group(['prefix' => 'spacebox'], function(){
    Route::get('/register', [UserController::class, 'registerForm'])->name('spacebox.register');
    Route::get('/login', [UserController::class, 'loginForm'])->name('spacebox.login');
    Route::post('/register/auth',[UserController::class, 'registerAction'])->name('spacebox.register.auth');
    Route::get('/', [HomeController::class, 'index'])->name('home.index');
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
});