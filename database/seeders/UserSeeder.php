<?php

namespace Database\Seeders;

use DB;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username'=>'Admin',
                'email'=>'buiduccong22052003@gmail.com',
                'password'=>Hash::make('admin123'),
                'role_id' => 1,
                'email_verified_at' => now()
            ]
        ]);
    }
}
