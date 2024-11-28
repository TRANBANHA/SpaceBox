<?php

use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
// Broadcast::routes(['middleware' => ['auth:sanctum']]);

// Broadcast::channel('chat.{roomId}', function (User $user, int $roomId) {
//     // Kiểm tra xem người dùng có thuộc phòng hay không
//     $room = Room::find($roomId); // Tìm phòng theo ID
    
//     if (!$room) {
//         return false; // Nếu phòng không tồn tại, không cho phép truy cập
//     }

//     // Kiểm tra xem người dùng có thuộc phòng hay không
//     return $room->users->contains($user->id);
// });