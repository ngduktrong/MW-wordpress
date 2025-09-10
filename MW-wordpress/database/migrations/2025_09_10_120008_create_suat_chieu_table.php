<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('suat_chieu', function (Blueprint $table) {
            $table->id('ma_suat_chieu');
            $table->unsignedBigInteger('ma_phim');
            $table->unsignedBigInteger('ma_phong');
            $table->dateTime('ngay_gio_chieu');
            $table->foreign('ma_phim')->references('ma_phim')->on('phim')->onDelete('cascade');
            $table->foreign('ma_phong')->references('ma_phong')->on('phong_chieu')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('suat_chieu');
    }
};
