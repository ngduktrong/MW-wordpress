<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('khach_hang', function (Blueprint $table) {
            $table->unsignedBigInteger('ma_nguoi_dung')->primary();
            $table->integer('diem_tich_luy')->default(0);
            $table->foreign('ma_nguoi_dung')->references('ma_nguoi_dung')->on('nguoi_dung')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('khach_hang');
    }
};
