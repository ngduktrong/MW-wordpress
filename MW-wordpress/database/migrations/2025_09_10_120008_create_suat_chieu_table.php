<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('SuatChieu', function (Blueprint $table) {
            $table->integer('MaSuatChieu')->autoIncrement()->primary(); // Sửa thành integer và autoIncrement
            $table->integer('MaPhim')->unsigned(); // Sửa thành integer
            $table->integer('MaPhong')->unsigned(); // Sửa thành integer
            $table->dateTime('NgayGioChieu');
            $table->foreign('MaPhim')->references('MaPhim')->on('Phim')->onDelete('cascade');
            $table->foreign('MaPhong')->references('MaPhong')->on('PhongChieu')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('SuatChieu'); // Sửa tên bảng thành SuatChieu
    }
};