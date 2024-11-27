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
        Schema::create('messages', function (Blueprint $table) {
            $table->id('message_id'); // Khóa chính cho bảng messages
            $table->unsignedBigInteger('user_id'); // ID của người gửi
            $table->unsignedBigInteger('room_id'); // ID của phòng chat
            $table->boolean('is_current_user')->default(false); // Đánh dấu tin nhắn của người dùng hiện tại
            $table->text('content'); // Nội dung tin nhắn
            $table->string('file_path')->nullable(); // Đường dẫn đến tệp (có thể null nếu không có tệp)
            
            $table->boolean('is_pinned')->default(false); // Đánh dấu tin nhắn có được ghim hay không
            $table->boolean('is_deleted')->default(false); // Đánh dấu tin nhắn đã bị xóa

            $table->timestamps(); // Timestamps cho created_at và updated_at
            $table->softDeletes(); // Cột để hỗ trợ soft deletes

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('room_id')
                ->references('room_id')
                ->on('rooms')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
