<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('PhongChieu')) {
            Schema::create('PhongChieu', function (Blueprint $table) {
                $table->increments('MaPhong'); // int auto_increment primary key
                $table->string('TenPhong', 255)->unique();
                $table->integer('SoLuongGhe')->unsigned();
                $table->string('LoaiPhong', 50);
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('PhongChieu');
    }
};
