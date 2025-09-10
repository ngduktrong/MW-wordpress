<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tai_khoan', function (Blueprint $table) {
            $table->string('ten_dang_nhap', 50)->primary();
            $table->string('mat_khau', 255);
            $table->enum('loai_tai_khoan', ['admin', 'user']);
            $table->unsignedBigInteger('ma_nguoi_dung')->unique()->nullable();
            $table->foreign('ma_nguoi_dung')->references('ma_nguoi_dung')->on('nguoi_dung')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('tai_khoan');
    }
};
