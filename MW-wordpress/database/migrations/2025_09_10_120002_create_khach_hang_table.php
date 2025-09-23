<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Chỉ tạo bảng nếu chưa tồn tại
        if (!Schema::hasTable('KhachHang')) {
            Schema::create('KhachHang', function (Blueprint $table) {
                $table->unsignedBigInteger('MaNguoiDung')->primary();
                $table->integer('DiemTichLuy')->default(0);

                $table->foreign('MaNguoiDung')
                      ->references('MaNguoiDung')->on('NguoiDung')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('KhachHang');
    }
};
