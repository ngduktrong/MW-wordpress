<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('nguoi_dung', function (Blueprint $table) {
            $table->id('ma_nguoi_dung');
            $table->string('ho_ten', 100);
            $table->string('so_dien_thoai', 15)->unique();
            $table->string('email', 100)->unique();
            $table->enum('loai_nguoi_dung', ['KhachHang', 'NhanVien']);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('nguoi_dung');
    }
};
