<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('Ghe', function (Blueprint $table) {
            $table->integer('MaPhong'); // Changed to match database type
            $table->string('SoGhe', 5); // Changed column name
            $table->primary(['MaPhong', 'SoGhe']); // Updated primary key
            $table->foreign('MaPhong')->references('MaPhong')->on('PhongChieu')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('Ghe'); // Updated table name
    }
};