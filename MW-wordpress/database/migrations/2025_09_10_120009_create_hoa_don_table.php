<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('hoa_don', function (Blueprint $table) {
            $table->id('ma_hoa_don');
            $table->unsignedBigInteger('ma_nhan_vien')->nullable();
            $table->unsignedBigInteger('ma_khach_hang')->nullable();
            $table->dateTime('ngay_lap')->useCurrent();
            $table->decimal('tong_tien', 10, 2)->unsigned();
            $table->foreign('ma_nhan_vien')->references('ma_nguoi_dung')->on('nhan_vien')->onDelete('set null');
            $table->foreign('ma_khach_hang')->references('ma_nguoi_dung')->on('khach_hang')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('hoa_don');
    }
};
