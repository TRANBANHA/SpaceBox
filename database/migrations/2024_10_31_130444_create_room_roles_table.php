<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('room_roles', function (Blueprint $table) {
            $table->id('room_role_id'); // Khóa chính
            $table->unsignedBigInteger('room_id'); // Phòng mà người dùng có vai trò
            $table->unsignedBigInteger('role_id'); // Vai trò của người dùng trong phòng
            $table->unsignedBigInteger('user_id'); // Người dùng trong phòng
            $table->timestamps();

            // Định nghĩa các khóa ngoại
            $table  ->foreign('room_id')
                    ->references('room_id')
                    ->on('rooms')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            
            $table  ->foreign('role_id')
                    ->references('role_id')
                    ->on('roles')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table  ->foreign('user_id')
                    ->references('user_id')
                    ->on('users')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_roles');
    }
};
