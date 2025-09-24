<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('TaiKhoan')) {
            Schema::create('TaiKhoan', function (Blueprint $table) {
                $table->string('TenDangNhap', 50)->primary();
                $table->string('MatKhau', 255);
                $table->enum('LoaiTaiKhoan', ['Admin', 'User'])->default('User');
                $table->unsignedBigInteger('MaNguoiDung')->unique()->nullable();

                // timestamps để dễ track thay đổi (created_at, updated_at)
                $table->timestamps();

                // foreign key (giữ như bạn có NguoiDung.MaNguoiDung)
                $table->foreign('MaNguoiDung')
                      ->references('MaNguoiDung')->on('NguoiDung')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('TaiKhoan');
    }
};
