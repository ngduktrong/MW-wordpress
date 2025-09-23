<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Chỉ tạo nếu chưa có bảng
        if (!Schema::hasTable('NhanVien')) {
            Schema::create('NhanVien', function (Blueprint $table) {
                $table->unsignedBigInteger('MaNguoiDung')->primary();
                $table->string('ChucVu', 50);
                $table->decimal('Luong', 10, 2)->unsigned();
                $table->enum('VaiTro', ['Admin', 'QuanLy', 'ThuNgan', 'BanVe']);

                $table->foreign('MaNguoiDung')
                      ->references('MaNguoiDung')->on('NguoiDung')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('NhanVien');
    }
};
