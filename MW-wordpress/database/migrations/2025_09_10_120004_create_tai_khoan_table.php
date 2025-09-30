<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Chỉ tạo bảng nếu chưa tồn tại
        if (!Schema::hasTable('TaiKhoan')) {
            Schema::create('TaiKhoan', function (Blueprint $table) {
                $table->string('TenDangNhap', 50)->primary();
                $table->string('MatKhau', 255);
                $table->enum('LoaiTaiKhoan', ['admin', 'user'])->default('user');
                $table->unsignedBigInteger('MaNguoiDung')->nullable();
                $table->timestamps(); // tạo cột created_at và updated_at

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
