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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id('room_id');
            $table->string('room_name'); 
            $table->string('avt_path')->default('https://res.cloudinary.com/dy6y1gpgm/image/upload/v1732224761/icon_group_leejca.png');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            $table->softDeletes();

            // Định nghĩa khóa ngoại cho created_by
            $table  ->foreign('created_by')
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
        Schema::dropIfExists('rooms');
    }
};
