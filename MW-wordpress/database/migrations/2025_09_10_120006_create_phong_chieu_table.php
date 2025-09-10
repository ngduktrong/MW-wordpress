<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('phong_chieu', function (Blueprint $table) {
            $table->id('ma_phong');
            $table->string('ten_phong', 255)->unique();
            $table->integer('so_luong_ghe')->unsigned();
            $table->string('loai_phong', 50);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('phong_chieu');
    }
};
