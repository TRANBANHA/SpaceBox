<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->insert([
            ['permission' => 'login'], // Đăng nhập
            ['permission' => 'register'], // Đăng ký
            ['permission' => 'manage_users'], // Add, edit, delete, list user,
            ['permission' => 'manage_rooms'], // Add, edit, delete, list room
            ['permission' => 'manage_room_users'], // Quyền quản lý người dùng trong phòng (Tạo phòng, thêm và xoá user)
            ['permission' => 'reset_password'],  // Reset password cho user
            ['permission' => 'change_profile'],  // Đổi thông tin user(bao gồm password)
            ['permission' => 'delete_message'], // Xoá tin nhắn
        ]);
    }
}
