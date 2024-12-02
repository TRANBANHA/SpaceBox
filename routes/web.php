<?php

use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Web\AccountController;
use App\Http\Controllers\Web\Admin\AdminController;
use App\Http\Controllers\Web\HomeController;
use Illuminate\Support\Facades\Route;


// Liên kết tới trang chưa login
Route::get('/', [HomeController::class, 'landingPage'])->name('spacebox.landingpage');



// Account
Route::group(['prefix' => 'account', 'as' => 'account.'], function(){
    Route::get('/register', [AccountController::class, 'registerForm'])->name('register');
    Route::post('/register',[AccountController::class, 'registerAction'])->name('register.auth');

    Route::get('/verify/{email}',[AccountController::class, 'verifyEmail'])->name('verify');

    Route::get('/login', [AccountController::class, 'loginForm'])->name('login');
    Route::post('/login',[AccountController::class, 'loginAction'])->name('login.auth');

    //Login Google
    Route::get('auth/google', [GoogleController::class, 'googleLogin'])->name('auth.google');
    Route::get('auth/google/callback', [GoogleController::class, 'googleAuthentication']);
   


    Route::get('/logout', [AccountController::class, 'logoutAction'])->name('logout');

    Route::get('/reset-password', [AccountController::class, 'forgotPassForm'])->name('forgotPassForm');
    Route::post('/reset-password', [AccountController::class, 'sendEmailResetPass'])->name('sendResetPass');


});


    
// Dành cho Admin
Route::group(['prefix' => 'admin', 'middleware' => 'check_admin' ,'as' => 'admin.'], function(){
    Route::get('/', [AdminController::class, 'index'])->name('index');
    // Quản lý người dùng
    Route::get('/quan-ly-nguoi-dung', [AdminController::class, 'getListUser'])->name('getListUser');

    Route::post('/quan-ly-nguoi-dung/resetpass', [AccountController::class, 'sendEmailResetPass'])->name('sendResetPass');
    
    Route::delete('/quan-ly-nguoi-dung/delete', [AdminController::class, 'deleteUser'])->name('deleteUsers');
    
    Route::get('/quan-ly-nguoi-dung/add', [AdminController::class, 'addUserForm'])->name('addUserForm');
    Route::post('/quan-ly-nguoi-dung/add', [AdminController::class, 'addUserAction'])->name('addUserAction');
    
    Route::get('/quan-ly-nguoi-dung/update/{user_id}', [AdminController::class, 'updateUserForm'])->name('updateUserForm');
    Route::patch('/quan-ly-nguoi-dung/update/{user_id}', [AdminController::class, 'updateProfileUser'])->name('updateProfileUser');
    
    Route::patch('/quan-ly-nguoi-dung/lock/{user_id}', [AdminController::class, 'lockAccountUser'])->name('lockAccountUser');
    Route::patch('/quan-ly-nguoi-dung/unlock/{user_id}', [AdminController::class, 'unlockAccountUser'])->name('unlockAccountUser');
    
    // Quản lý phòng chat
    Route::get('/quan-ly-phong-chat', [AdminController::class, 'getListRoom'])->name('getListRoom');
    Route::delete('/quan-ly-phong-chat/delete', [AdminController::class, 'deleteRoom'])->name('deleteRoom');
    Route::post('/quan-ly-phong-chat/addroom', [AdminController::class, 'addRoom'])->name('addRoom');
    Route::patch('/quan-ly-phong-chat/updateroom/{room_id}', [AdminController::class, 'updateRoom'])->name('updateRoom');

    
    // Xem, sửa profile
    Route::get('/profile', [AdminController::class, 'getProfile'])->name('getProfile');
    Route::patch('/update-profile', [AdminController::class, 'updateProfile'])->name('updateProfile');

    // Change password
    Route::get('/change-pass', [AdminController::class, 'changePassForm'])->name('changePassForm');
    Route::put('/change-pass', [AdminController::class, 'changePassAction'])->name('changePass');
    

    // Đăng nhập vào spacebox
    Route::get('/spacebox/r{room_id}', [HomeController::class, 'chat'])->name('home.chat');
    
    Route::post('/spacebox/addroom', [HomeController::class, 'addroom'])->name('chat.addroom');
    

    Route::post('/sendFile', [HomeController::class, 'sendFile'])->name('chat.sendFile');
    Route::post('/spacebox/sendMess', [HomeController::class, 'sendMessage'])->name('chat.sendMess');

    Route::patch('/pinMess/{message_id}', [HomeController::class, 'pinnedMessage'])->name('chat.pinMess');
    Route::patch('/unpinMess/{message_id}', [HomeController::class, 'unpinnedMessage'])->name('chat.unpinMess');

    Route::delete('/deleteMess/{message_id}', [HomeController::class, 'deleteMessage'])->name('chat.deleteMess');

    
    Route::patch('/chat/updateRoom', [HomeController::class, 'updateRoom'])->name('userUpdateRoom');
});


// Dành cho Users
Route::group(['prefix' => 'spacebox', 'middleware' => 'check_user' ,'as' => 'spacebox.'], function(){

    // Route::get('/', [HomeController::class, 'chat'])->name('home.chat');
    Route::get('/r{room_id}', [HomeController::class, 'chat'])->name('home.chat');
    // Route::get('/messages/{room_id}', [HomeController::class, 'getMessagesInRoom'])->name('chat.messages');

    Route::post('/addroom', [HomeController::class, 'addroom'])->name('chat.addroom');

    Route::post('/sendFile', [HomeController::class, 'sendFile'])->name('chat.sendFile');
    Route::post('/sendMess', [HomeController::class, 'sendMessage'])->name('chat.sendMess');

    Route::patch('/pinMess/{message_id}', [HomeController::class, 'pinnedMessage'])->name('chat.pinMess');
    Route::patch('/unpinMess/{message_id}', [HomeController::class, 'unpinnedMessage'])->name('chat.unpinMess');
    
    Route::delete('/deleteMess/{message_id}', [HomeController::class, 'deleteMessage'])->name('chat.deleteMess');

    Route::patch('/updateProfile', [AccountController::class, 'updateProfile'])->name('updateProfile');   
    Route::put('/updatePass', [AccountController::class, 'updatePass'])->name('updatePass');

    Route::patch('/chat/updateRoom', [HomeController::class, 'updateRoom'])->name('userUpdateRoom');

});




