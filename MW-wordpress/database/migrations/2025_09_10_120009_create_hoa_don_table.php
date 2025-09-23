<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('HoaDon')) {
            Schema::create('HoaDon', function (Blueprint $table) {
                $table->id('MaHoaDon'); // BIGINT AUTO_INCREMENT PRIMARY KEY
                $table->unsignedBigInteger('MaNhanVien')->nullable();
                $table->unsignedBigInteger('MaKhachHang')->nullable();
                $table->dateTime('NgayLap')->useCurrent();
                $table->decimal('TongTien', 10, 2)->unsigned();

                // FK
                $table->foreign('MaNhanVien')
                      ->references('MaNguoiDung')->on('NhanVien')
                      ->onDelete('set null');

                $table->foreign('MaKhachHang')
                      ->references('MaNguoiDung')->on('KhachHang')
                      ->onDelete('set null');
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('HoaDon');
    }
};
