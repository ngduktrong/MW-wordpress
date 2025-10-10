<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration {
    public function up(): void {
        // Chỉ tạo bảng nếu chưa tồn tại
        if (!Schema::hasTable('HoaDon')) {
            Schema::create('HoaDon', function (Blueprint $table) {
                $table->id('MaHoaDon');
                $table->unsignedBigInteger('MaNhanVien')->nullable();
                $table->unsignedBigInteger('MaKhachHang')->nullable();
                $table->dateTime('NgayLap')->useCurrent();
                $table->decimal('TongTien', 10, 2)->default(0);
                
                Log::info('Tạo bảng HoaDon thành công');
            });
        } else {
            // Bảng đã tồn tại - KHÔNG làm gì cả
            Log::info('Bảng HoaDon đã tồn tại, giữ nguyên cấu trúc');
            
            // Ghi log cấu trúc hiện tại để kiểm tra
            $columns = DB::select('DESCRIBE HoaDon');
            Log::info('Cấu trúc HoaDon hiện tại:', $columns);
        }
    }

    public function down(): void {
        // KHÔNG xóa bảng trong production
        if (app()->environment('local', 'testing')) {
            Schema::dropIfExists('HoaDon');
        }
    }
};