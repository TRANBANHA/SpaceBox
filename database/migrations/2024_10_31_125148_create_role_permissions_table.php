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
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id('role_permission_id'); 
            $table->unsignedBigInteger('role_id'); 
            $table->unsignedBigInteger('permission_id'); 
            $table->boolean('allowed')->default(true); // Cho phép hoặc không cho phép quyền
            $table->timestamps();
            $table->softDeletes();

            // Định nghĩa khóa ngoại
            $table  ->foreign('role_id')
                    ->references('role_id')
                    ->on('roles')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table  ->foreign('permission_id')
                    ->references('permission_id')
                    ->on('permissions')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
