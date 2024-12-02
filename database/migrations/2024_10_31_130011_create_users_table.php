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
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('google_id')->nullable();
            $table->string('email')->unique(); 
            $table->string('password');
            $table->string('username');
            $table->string('img_path')->default('https://res.cloudinary.com/dy6y1gpgm/image/upload/v1731680383/male_q2q91r.png');
            $table->boolean('gender')->default(true); // Mặc định true là nam
            $table->text('description')->nullable();
            $table->unsignedBigInteger('role_id')->default(3);  // Người dùng mới mặc định quyền là Normal
            $table->timestamp('email_verified_at')->nullable(); 
            $table->boolean('status')->default(true);
            
            $table->rememberToken();
            $table->timestamps(); 
            $table->softDeletes();

            // Định nghĩa khóa ngoại cho role_id
            $table  ->foreign('role_id')
                    ->references('role_id')
                    ->on('roles')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
