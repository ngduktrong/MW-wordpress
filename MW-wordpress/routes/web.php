<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PhimController;
use App\Http\Controllers\PhongChieuController;
use App\Http\Controllers\SuatChieuController;
use App\Http\Controllers\GheController;
use App\Http\Controllers\NguoiDungController;
use App\Http\Controllers\TaiKhoanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\KhachHangController;
use App\Http\Controllers\NhanVienController;
use App\Http\Controllers\VeController;
use App\Http\Controllers\HoaDonController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Route đăng nhập/đăng ký
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Các route admin cần auth
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('AdminDashBoard');
    })->name('admin.dashboard');
});

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
// Hiển thị danh sách + form
Route::get('/ghe', [GheController::class, 'index'])->name('ghe.index');
Route::post('/ghe', [GheController::class, 'store'])->name('ghe.store');
Route::put('/ghe/{maPhong}/{soGhe}', [GheController::class, 'update'])->name('ghe.update');
Route::delete('/ghe/{maPhong}/{soGhe}', [GheController::class, 'destroy'])->name('ghe.destroy');
Route::get('/ghe/edit/{maPhong}/{soGhe}', [GheController::class, 'edit'])->name('ghe.edit');

// HoaDon Routes
Route::get('/hoadon', [HoaDonController::class, 'index']);
Route::post('/hoadon', [HoaDonController::class, 'store']);
Route::get('/hoadon/{id}', [HoaDonController::class, 'show']);
Route::put('/hoadon/{id}', [HoaDonController::class, 'update']);
Route::delete('/hoadon/{id}', [HoaDonController::class, 'destroy']);

// HoaDon Search & Statistics
Route::get('/hoadon/khachhang/{maKhachHang}', [HoaDonController::class, 'getByMaKhachHang']);
Route::get('/hoadon/ngay/{ngay}', [HoaDonController::class, 'getByNgayLap']);
Route::post('/hoadon/khoangngay', [HoaDonController::class, 'getByKhoangNgay']);
Route::get('/hoadon/doanhthu/ngay/{ngay}', [HoaDonController::class, 'getTongDoanhThuTheoNgay']);
Route::post('/hoadon/doanhthu/khoangngay', [HoaDonController::class, 'getTongDoanhThuTheoKhoangNgay']);
Route::put('/hoadon/capnhatngaylap/{maHoaDon}', [HoaDonController::class, 'capNhatNgayLapTuVe']);

Route::get('/ve', [VeController::class, 'index']);
Route::post('/ve', [VeController::class, 'store']);
Route::get('/ve/{id}', [VeController::class, 'show']);
Route::put('/ve/{id}', [VeController::class, 'update']);
Route::delete('/ve/{id}', [VeController::class, 'destroy']);

// Ve Additional Routes
Route::post('/ve/danhsach', [VeController::class, 'getVesByIds']);
Route::put('/ve/thanhtoan/{id}', [VeController::class, 'updateTrangThaiVeToPaid']);
Route::get('/ve/hoadon/{maHoaDon}', [VeController::class, 'getVeByMaHoaDon']);
Route::get('/ve/khachhang/{maKhachHang}', [VeController::class, 'getVeByMaKhachHang']);
Route::get('/ve/suatchieu/{maSuatChieu}', [VeController::class, 'getSoGheDaDatBySuatChieu']);
Route::get('/ve/thongke/sovedathanhtoan', [VeController::class, 'getSoVeDaThanhToan']);

// Admin Views
Route::get('/admin/hoadon', [HoaDonController::class, 'index']);
Route::get('/admin/ve', [VeController::class, 'index']);
// nguoiDUng Routes
Route::prefix('admin')->group(function () {
    Route::get('/nguoidung', [NguoiDungController::class, 'adminIndex'])->name('admin.nguoidung.index');
    Route::get('/nguoidung/create', [NguoiDungController::class, 'create'])->name('admin.nguoidung.create'); // thêm
    Route::post('/nguoidung', [NguoiDungController::class, 'store'])->name('admin.nguoidung.store');
    Route::get('/nguoidung/{id}/edit', [NguoiDungController::class, 'edit'])->name('admin.nguoidung.edit');
    Route::put('/nguoidung/{id}', [NguoiDungController::class, 'update'])->name('admin.nguoidung.update');
    Route::delete('/nguoidung/{id}', [NguoiDungController::class, 'destroy'])->name('admin.nguoidung.destroy');
});
// TaiKhoan Routes
Route::prefix('admin')->group(function () {
    Route::get('/taikhoan', [TaiKhoanController::class, 'adminIndex'])->name('admin.taikhoan.index');

    // đặt route static /users/... lên trước các route có param
    Route::get('/taikhoan/users/without-accounts', [TaiKhoanController::class, 'getUsersWithoutAccounts'])->name('admin.taikhoan.users.without.accounts');

    Route::post('/taikhoan', [TaiKhoanController::class, 'store'])->name('admin.taikhoan.store');

    Route::get('/taikhoan/{tenDangNhap}/edit', [TaiKhoanController::class, 'edit'])->name('admin.taikhoan.edit')->where('tenDangNhap', '.+');

    Route::put('/taikhoan/{tenDangNhap}', [TaiKhoanController::class, 'update'])->name('admin.taikhoan.update')->where('tenDangNhap', '.+');

    Route::delete('/taikhoan/{tenDangNhap}', [TaiKhoanController::class, 'destroy'])->name('admin.taikhoan.destroy')->where('tenDangNhap', '.+');
});
Route::prefix('admin')->group(function () {
    // Trang quản lý khách hàng
    Route::get('/khach-hang', [KhachHangController::class, 'index'])
        ->name('admin.khachhang.index');
        Route::get('/khach-hang/check/{maNguoiDung}', [KhachHangController::class, 'checkUser'])
        ->name('admin.khachhang.checkUser');

    // Thêm khách hàng
    Route::post('/khach-hang', [KhachHangController::class, 'store'])
        ->name('admin.khachhang.store');

    // Sửa khách hàng
    Route::put('/khach-hang/{id}', [KhachHangController::class, 'update'])
        ->name('admin.khachhang.update');

    // Xóa khách hàng
    Route::delete('/khach-hang/{id}', [KhachHangController::class, 'destroy'])
        ->name('admin.khachhang.destroy');

    
});
Route::get('/admin/nhanvien', function () {
    return view('AdminNhanVIen'); // resources/views/AdminNhanVIen.blade.php
})->name('admin.nhanvien.page');

// API endpoints (JSON) dùng cho AJAX
Route::prefix('admin')->group(function () {
    Route::get('/nhanvien/list', [NhanVienController::class, 'index'])->name('admin.nhanvien.list');
    Route::post('/nhanvien', [NhanVienController::class, 'store'])->name('admin.nhanvien.store');
    Route::put('/nhanvien/{id}', [NhanVienController::class, 'update'])->name('admin.nhanvien.update');
    Route::delete('/nhanvien/{id}', [NhanVienController::class, 'destroy'])->name('admin.nhanvien.destroy');
    Route::get('/nhanvien/nguoidung-chua-co', [NhanVienController::class, 'getNguoiDungChuaCoTaiKhoan'])
         ->name('admin.nhanvien.nguoidung_chua_co');
});

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
