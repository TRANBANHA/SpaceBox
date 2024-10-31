<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Role_PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('role_permissions')->insert([
            // Admin
            ['role_id' => 1, 'permission_id' => 1, 'allowed' => true,], // Đăng nhập
            ['role_id' => 1, 'permission_id' => 2, 'allowed' => false,], // Đăng ký
            ['role_id' => 1, 'permission_id' => 3, 'allowed' => true,], // Quản lý user
            ['role_id' => 1, 'permission_id' => 4, 'allowed' => true,], // Quản lý room
            ['role_id' => 1, 'permission_id' => 5, 'allowed' => true,], // Quản lý người dùng trong room
            ['role_id' => 1, 'permission_id' => 6, 'allowed' => true,], // Reset pass
            ['role_id' => 1, 'permission_id' => 7, 'allowed' => true,], // Change profile
            ['role_id' => 1, 'permission_id' => 8, 'allowed' => true,], // Delete pass
            
            // Moderate User
            ['role_id' =>2, 'permission_id' => 1, 'allowed' => true,],
            ['role_id' =>2, 'permission_id' => 2, 'allowed' => true,],
            ['role_id' =>2, 'permission_id' => 3, 'allowed' => false,],
            ['role_id' =>2, 'permission_id' => 4, 'allowed' => false,],
            ['role_id' =>2, 'permission_id' => 5, 'allowed' => true,],
            ['role_id' =>2, 'permission_id' => 6, 'allowed' => false,],
            ['role_id' =>2, 'permission_id' => 7, 'allowed' => true,],
            ['role_id' =>2, 'permission_id' => 8, 'allowed' => true,],

            // Normal User
            ['role_id' => 3, 'permission_id' => 1, 'allowed' => true,],
            ['role_id' => 3, 'permission_id' => 2, 'allowed' => true,],
            ['role_id' => 3, 'permission_id' => 3, 'allowed' => false,],
            ['role_id' => 3, 'permission_id' => 4, 'allowed' => false,],
            ['role_id' => 3, 'permission_id' => 5, 'allowed' => false,],
            ['role_id' => 3, 'permission_id' => 6, 'allowed' => false,],
            ['role_id' => 3, 'permission_id' => 7, 'allowed' => true,],
            ['role_id' => 3, 'permission_id' => 8, 'allowed' => false,],
        ]);
    }
}
