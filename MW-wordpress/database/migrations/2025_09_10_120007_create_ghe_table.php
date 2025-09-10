<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ghe', function (Blueprint $table) {
            $table->unsignedBigInteger('ma_phong');
            $table->string('so_ghe', 5);
            $table->primary(['ma_phong', 'so_ghe']);
            $table->foreign('ma_phong')->references('ma_phong')->on('phong_chieu')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('ghe');
    }
};
