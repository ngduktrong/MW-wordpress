<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('NguoiDung')) {
            Schema::create('NguoiDung', function (Blueprint $table) {
                $table->id('MaNguoiDung');
                $table->string('HoTen', 100);
                $table->string('SoDienThoai', 15)->unique();
                $table->string('Email', 100)->unique();
                $table->enum('LoaiNguoiDung', ['KhachHang', 'NhanVien']);
                $table->timestamps();
            });
        } else {
            // Thêm cột created_at, updated_at nếu bảng đã tồn tại
            Schema::table('NguoiDung', function (Blueprint $table) {
                if (!Schema::hasColumn('NguoiDung', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('NguoiDung', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('NguoiDung');
    }
};