<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePhimTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Phim', function (Blueprint $table) {
            $table->increments('MaPhim');
            $table->string('TenPhim', 100);
            $table->unsignedInteger('ThoiLuong');
            $table->date('NgayKhoiChieu');
            $table->string('NuocSanXuat', 50);
            $table->string('DinhDang', 20);
            $table->text('MoTa')->nullable();
            $table->string('DaoDien', 100);
            $table->text('DuongDanPoster')->nullable();

            // Index theo yêu cầu
            $table->index('TenPhim');
        });

        // Thử thêm CHECK constraint — nhưng bọc try/catch để không phá migration nếu DB không hỗ trợ
        try {
            $driver = DB::connection()->getDriverName();

            // MySQL 8+ và PostgreSQL hỗ trợ CHECK; các DB khác có thể không
            if (in_array($driver, ['mysql', 'pgsql'])) {
                // MySQL: dùng backticks; PostgreSQL sẽ chấp nhận nhưng với double quotes - nhiều DB vẫn chấp nhận chung lệnh này
                DB::statement("ALTER TABLE `Phim` ADD CONSTRAINT chk_thoiluong_positive CHECK (ThoiLuong > 0)");
            }
        } catch (\Exception $e) {
            // Không muốn migration fail chỉ vì CHECK không được hỗ trợ.
            // Nếu muốn log, có thể dùng: \Log::warning('Could not add CHECK constraint: '.$e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Phim');
    }
}
