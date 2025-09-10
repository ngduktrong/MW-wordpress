<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('phim', function (Blueprint $table) {
            $table->id('ma_phim');
            $table->string('ten_phim', 100);
            $table->integer('thoi_luong')->unsigned();
            $table->date('ngay_khoi_chieu');
            $table->string('nuoc_san_xuat', 50);
            $table->string('dinh_dang', 20);
            $table->text('mo_ta')->nullable();
            $table->string('dao_dien', 100);
            $table->text('duong_dan_poster')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('phim');
    }
};
