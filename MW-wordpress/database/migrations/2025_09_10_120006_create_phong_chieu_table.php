<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('PhongChieu', function (Blueprint $table) {
            $table->integer('MaPhong')->autoIncrement()->primary(); // Sửa thành integer và autoIncrement
            $table->string('TenPhong', 255)->unique();
            $table->integer('SoLuongGhe')->unsigned();
            $table->string('LoaiPhong', 50);
            // Loại bỏ timestamps vì không có trong database
        });
    }

    public function down(): void {
        Schema::dropIfExists('PhongChieu'); // Sửa tên bảng thành PhongChieu
    }
};