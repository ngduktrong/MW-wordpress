<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('Ve')) {
            Schema::create('Ve', function (Blueprint $table) {
                $table->id('MaVe');
                $table->unsignedInteger('MaSuatChieu'); // khớp với SuatChieu
                $table->integer('MaPhong'); // khớp với PhongChieu
                $table->string('SoGhe', 5);
                $table->unsignedBigInteger('MaHoaDon')->nullable();
                $table->decimal('GiaVe', 10, 2)->unsigned();
                $table->enum('TrangThai', ['available', 'booked', 'paid', 'cancelled', 'pending'])->default('available');
                $table->dateTime('NgayDat')->nullable();

                // FK
                $table->foreign('MaSuatChieu')
                      ->references('MaSuatChieu')->on('SuatChieu')
                      ->onDelete('cascade');

                $table->foreign('MaHoaDon')
                      ->references('MaHoaDon')->on('HoaDon')
                      ->onDelete('set null');

                $table->foreign(['MaPhong', 'SoGhe'])
                      ->references(['MaPhong', 'SoGhe'])->on('Ghe')
                      ->onDelete('cascade');

                // Mỗi suất chiếu không thể trùng ghế
                $table->unique(['MaSuatChieu', 'SoGhe']);
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('Ve');
    }
};
