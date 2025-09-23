<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('Ghe')) {
            Schema::create('Ghe', function (Blueprint $table) {
                $table->integer('MaPhong');
                $table->string('SoGhe', 5);
                $table->primary(['MaPhong', 'SoGhe']); // composite PK

                $table->foreign('MaPhong')
                      ->references('MaPhong')
                      ->on('PhongChieu')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('Ghe');
    }
};
