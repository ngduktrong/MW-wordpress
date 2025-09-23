<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('SuatChieu')) {
            Schema::create('SuatChieu', function (Blueprint $table) {
                $table->increments('MaSuatChieu'); // PK auto increment
                $table->unsignedInteger('MaPhim');
                $table->integer('MaPhong');
                $table->dateTime('NgayGioChieu');

                // FK
                $table->foreign('MaPhim')
                      ->references('MaPhim')->on('Phim')
                      ->onDelete('cascade');

                $table->foreign('MaPhong')
                      ->references('MaPhong')->on('PhongChieu')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('SuatChieu');
    }
};
