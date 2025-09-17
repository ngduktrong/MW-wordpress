<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PhimController;
use App\Http\Controllers\PhongChieuController;
use App\Http\Controllers\SuatChieuController;
use App\Http\Controllers\GheController;

// Trang quản lý phim (hiển thị view AdminPhim.php)
Route::get('/admin/phim', [PhimController::class, 'showAdminPage'])->name('admin.phim');

// CRUD phim (tận dụng BaseCrudController)
Route::resource('phim', PhimController::class);
// Routes cho quản lý phòng chiếu
Route::prefix('admin')->group(function () {
    Route::get('/phongchieu', [PhongChieuController::class, 'index'])->name('admin.phongchieu.index');
    Route::post('/phongchieu', [PhongChieuController::class, 'store'])->name('admin.phongchieu.store');
    Route::put('/phongchieu/{id}', [PhongChieuController::class, 'update'])->name('admin.phongchieu.update');
    Route::delete('/phongchieu/{id}', [PhongChieuController::class, 'destroy'])->name('admin.phongchieu.destroy');
});
// Routes cho quản lý suất chiếu
Route::prefix('admin')->group(function () {
    Route::get('/suatchieu', [SuatChieuController::class, 'index'])->name('admin.suatchieu.index');
    Route::post('/suatchieu', [SuatChieuController::class, 'store'])->name('admin.suatchieu.store');
    Route::put('/suatchieu/{id}', [SuatChieuController::class, 'update'])->name('admin.suatchieu.update');
    Route::delete('/suatchieu/{id}', [SuatChieuController::class, 'destroy'])->name('admin.suatchieu.destroy');
});
// Hiển thị danh sách + form
Route::get('/ghe', [GheController::class, 'index'])->name('ghe.index');
Route::post('/ghe', [GheController::class, 'store'])->name('ghe.store');
Route::put('/ghe/{maPhong}/{soGhe}', [GheController::class, 'update'])->name('ghe.update');
Route::delete('/ghe/{maPhong}/{soGhe}', [GheController::class, 'destroy'])->name('ghe.destroy');
Route::get('/ghe/edit/{maPhong}/{soGhe}', [GheController::class, 'edit'])->name('ghe.edit');
// Route test database (giữ nguyên cho debug)
Route::get('/test-db', function () {
    try {
        $connName = DB::getDefaultConnection();
        $dbName = DB::connection()->getDatabaseName();
        $driver = DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME);
        $now = DB::select('SELECT NOW() as now_time')[0]->now_time ?? null;

        $tablesRaw = DB::select('SHOW TABLES');
        $tables = array_map(function ($t) { 
            $a = (array)$t; 
            return array_values($a)[0]; 
        }, $tablesRaw);

        $likeUpper = DB::select("SHOW TABLES LIKE 'Phim'");
        $likeLower = DB::select("SHOW TABLES LIKE 'phim'");
        $info = DB::select(
            "SELECT TABLE_NAME FROM information_schema.tables WHERE table_schema = ? AND LOWER(TABLE_NAME) = ?",
            [$dbName, 'phim']
        );

        $phim_exists = in_array('Phim', $tables, true) ? 'yes' : 'no';
        $phim_exists_lower = in_array('phim', $tables, true) ? 'yes' : 'no';

        return [
            'default_connection' => $connName,
            'database' => $dbName,
            'driver' => $driver,
            'now' => $now,
            'tables_count' => count($tables),
            'tables_sample' => array_slice($tables, 0, 40),
            'Phim_present_exact' => $phim_exists,
            'phim_present_lower' => $phim_exists_lower,
            'SHOW_TABLES_like_Phim' => count($likeUpper),
            'SHOW_TABLES_like_phim' => count($likeLower),
            'information_schema_lookup' => array_map(fn($r)=>(array)$r, $info),
        ];
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
    }
});
