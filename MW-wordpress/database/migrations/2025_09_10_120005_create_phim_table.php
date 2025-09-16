<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Phim', function (Blueprint $table) {
            $table->increments('MaPhim'); // INT AUTO_INCREMENT PRIMARY KEY
            $table->string('TenPhim', 100); // VARCHAR(100) NOT NULL
            $table->integer('ThoiLuong')->unsigned(); // INT CHECK > 0
            $table->date('NgayKhoiChieu'); // DATE NOT NULL
            $table->string('NuocSanXuat', 50); // NVARCHAR(50)
            $table->string('DinhDang', 20); // NVARCHAR(20)
            $table->text('MoTa')->nullable(); // TEXT
            $table->string('DaoDien', 100); // NVARCHAR(100)
            $table->text('DuongDanPoster')->nullable(); // TEXT
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Phim');
    }
};
