<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('nhan_vien', function (Blueprint $table) {
            $table->unsignedBigInteger('ma_nguoi_dung')->primary();
            $table->string('chuc_vu', 50);
            $table->decimal('luong', 10, 2)->unsigned();
            $table->enum('vai_tro', ['Admin', 'QuanLy', 'ThuNgan', 'BanVe']);
            $table->foreign('ma_nguoi_dung')->references('ma_nguoi_dung')->on('nguoi_dung')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('nhan_vien');
    }
};
