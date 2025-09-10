<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ve', function (Blueprint $table) {
            $table->id('ma_ve');
            $table->unsignedBigInteger('ma_suat_chieu');
            $table->unsignedBigInteger('ma_phong');
            $table->string('so_ghe', 5);
            $table->unsignedBigInteger('ma_hoa_don')->nullable();
            $table->decimal('gia_ve', 10, 2)->unsigned();
            $table->enum('trang_thai', ['available', 'booked', 'paid', 'cancelled', 'pending'])->default('available');
            $table->dateTime('ngay_dat')->nullable();

            $table->foreign('ma_suat_chieu')->references('ma_suat_chieu')->on('suat_chieu')->onDelete('cascade');
            $table->foreign('ma_hoa_don')->references('ma_hoa_don')->on('hoa_don')->onDelete('set null');
            $table->foreign(['ma_phong', 'so_ghe'])->references(['ma_phong', 'so_ghe'])->on('ghe')->onDelete('cascade');
            $table->unique(['ma_suat_chieu', 'so_ghe']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('ve');
    }
};
