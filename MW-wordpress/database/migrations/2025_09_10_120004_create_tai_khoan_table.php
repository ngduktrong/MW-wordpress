<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('TaiKhoan', function (Blueprint $table) {
            // TenDangNhap là PK (chuỗi)
            $table->string('TenDangNhap', 50)->primary();
            $table->string('MatKhau', 255);
            // chuẩn hoá enum về lowercase để đồng nhất với seed/data
            $table->enum('LoaiTaiKhoan', ['admin', 'user'])->default('user');

            // Lưu ý: nếu NguoiDung.MaNguoiDung là bigIncrements(), giữ unsignedBigInteger.
            // Nếu NguoiDung dùng increments() (INT), đổi sang unsignedInteger.
            $table->unsignedBigInteger('MaNguoiDung')->nullable()->unique();

            // FK - tham chiếu cột MaNguoiDung ở bảng NguoiDung
            $table->foreign('MaNguoiDung')->references('MaNguoiDung')->on('NguoiDung')->onDelete('cascade');

            // optional timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('TaiKhoan', function (Blueprint $table) {
            // drop FK trước
            if (Schema::hasColumn('TaiKhoan', 'MaNguoiDung')) {
                $table->dropForeign(['MaNguoiDung']);
            }
        });

        Schema::dropIfExists('TaiKhoan');
    }
};
