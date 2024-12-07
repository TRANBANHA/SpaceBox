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
        Schema::create('user_in_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id'); // ID của phòng
            $table->unsignedBigInteger('user_id'); // ID của người dùng
            $table->timestamps(); // Lưu thời gian tạo và cập nhật bản ghi

            // Định nghĩa khóa ngoại
            $table->foreign('room_id')->references('room_id')->on('rooms')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

            // Đảm bảo không cho phép một user tham gia nhiều lần cùng một room
            $table->unique(['room_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_in_rooms');
    }
};
