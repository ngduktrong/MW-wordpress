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
use App\Http\Controllers\KiemTraVeSapChieuController;
use App\Http\Controllers\CustomerHomeController;
use App\Http\Controllers\CustomerSChieuController;
use App\Http\Controllers\CustomerGheController;
use App\Http\Controllers\CustomerVeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ThongBaoController;

// ========================= ROUTE MỞ ĐẦU =========================
Route::get('/', function () {
    return view('welcome');
});

// ========================= ROUTE LOGIN/REGISTER =========================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// ========================= ROUTE ADMIN (CÓ AUTH & ROLE) =========================
Route::middleware(['auth','role:admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Quản lý phim
        Route::get('/phim', [PhimController::class, 'showAdminPage'])->name('admin.phim');
        Route::resource('movies', PhimController::class)->names([
            'index' => 'phim.index',
            'create' => 'phim.create',
            'store' => 'phim.store',
            'show' => 'phim.show',
            'edit' => 'phim.edit',
            'update' => 'phim.update',
            'destroy' => 'phim.destroy'
        ])->parameters(['movies' => 'phim']);

        // Quản lý phòng chiếu
        Route::get('/phongchieu', [PhongChieuController::class, 'index'])->name('admin.phongchieu.index');
        Route::post('/phongchieu', [PhongChieuController::class, 'store'])->name('admin.phongchieu.store');
        Route::put('/phongchieu/{id}', [PhongChieuController::class, 'update'])->name('admin.phongchieu.update');
        Route::delete('/phongchieu/{id}', [PhongChieuController::class, 'destroy'])->name('admin.phongchieu.destroy');

        // Quản lý suất chiếu
        Route::get('/suatchieu', [SuatChieuController::class, 'index'])->name('admin.suatchieu.index');
        Route::post('/suatchieu', [SuatChieuController::class, 'store'])->name('admin.suatchieu.store');
        Route::put('/suatchieu/{id}', [SuatChieuController::class, 'update'])->name('admin.suatchieu.update');
        Route::delete('/suatchieu/{id}', [SuatChieuController::class, 'destroy'])->name('admin.suatchieu.destroy');

        // Quản lý ghế 
        Route::get('/ghe', [GheController::class, 'index'])->name('ghe.index');
        Route::post('/ghe', [GheController::class, 'store'])->name('ghe.store');
        Route::get('/ghe/edit/{maPhong}/{soGhe}', [GheController::class, 'edit'])->name('ghe.edit');
        Route::put('/ghe/{maPhong}/{soGhe}', [GheController::class, 'update'])->name('ghe.update');
        Route::delete('/ghe/{maPhong}/{soGhe}', [GheController::class, 'destroy'])->name('ghe.destroy');

        // Quản lý hóa đơn 
        Route::get('/hoadon', [HoaDonController::class, 'index'])->name('admin.hoadon.index');
        Route::post('/hoadon', [HoaDonController::class, 'store']);
        Route::delete('/hoadon/{id}', [HoaDonController::class, 'destroy']);
        Route::get('/hoadon/khachhang/{maKhachHang}', [HoaDonController::class, 'getByMaKhachHang']);
        Route::get('/hoadon/ngay/{ngay}', [HoaDonController::class, 'getByNgayLap']);
        Route::post('/hoadon/khoangngay', [HoaDonController::class, 'getByKhoangNgay']);
        Route::get('/hoadon/doanhthu/ngay/{ngay}', [HoaDonController::class, 'getTongDoanhThuTheoNgay']);
        Route::post('/hoadon/doanhthu/khoangngay', [HoaDonController::class, 'getTongDoanhThuTheoKhoangNgay']);
        Route::put('/hoadon/capnhatngaylap/{maHoaDon}', [HoaDonController::class, 'capNhatNgayLapTuVe']);

        // Quản lý vé
        Route::get('/ve', [VeController::class, 'index'])->name('admin.ve.index');
        Route::post('/ve', [VeController::class, 'store']);
        Route::get('/ve/{id}', [VeController::class, 'show']);
        Route::put('/ve/{id}', [VeController::class, 'update']);
        Route::delete('/ve/{id}', [VeController::class, 'destroy']);
        Route::post('/ve/danhsach', [VeController::class, 'getVesByIds']);
        Route::put('/ve/thanhtoan/{id}', [VeController::class, 'updateTrangThaiVeToPaid']);
        Route::get('/ve/hoadon/{maHoaDon}', [VeController::class, 'getVeByMaHoaDon']);
        Route::get('/ve/khachhang/{maKhachHang}', [VeController::class, 'getVeByMaKhachHang']);
        Route::get('/ve/suatchieu/{maSuatChieu}', [VeController::class, 'getSoGheDaDatBySuatChieu']);
        Route::get('/ve/thongke/sovedathanhtoan', [VeController::class, 'getSoVeDaThanhToan']);

        // Quản lý người dùng 
        Route::get('/nguoidung', [NguoiDungController::class, 'adminIndex'])->name('admin.nguoidung.index');
        Route::get('/nguoidung/create', [NguoiDungController::class, 'create'])->name('admin.nguoidung.create');
        Route::post('/nguoidung', [NguoiDungController::class, 'store'])->name('admin.nguoidung.store');
        Route::get('/nguoidung/{id}/edit', [NguoiDungController::class, 'edit'])->name('admin.nguoidung.edit');
        Route::put('/nguoidung/{id}', [NguoiDungController::class, 'update'])->name('admin.nguoidung.update');
        Route::delete('/nguoidung/{id}', [NguoiDungController::class, 'destroy'])->name('admin.nguoidung.destroy');

        // Quản lý tài khoản
        Route::get('/taikhoan', [TaiKhoanController::class, 'adminIndex'])->name('admin.taikhoan.index');
        Route::get('/taikhoan/users/without-accounts', [TaiKhoanController::class, 'getUsersWithoutAccounts'])->name('admin.taikhoan.users.without.accounts');
        Route::post('/taikhoan', [TaiKhoanController::class, 'store'])->name('admin.taikhoan.store');
        Route::get('/taikhoan/{tenDangNhap}/edit', [TaiKhoanController::class, 'edit'])->name('admin.taikhoan.edit')->where('tenDangNhap', '.+');
        Route::put('/taikhoan/{tenDangNhap}', [TaiKhoanController::class, 'update'])->name('admin.taikhoan.update')->where('tenDangNhap', '.+');
        Route::delete('/taikhoan/{tenDangNhap}', [TaiKhoanController::class, 'destroy'])->name('admin.taikhoan.destroy')->where('tenDangNhap', '.+');

        // Quản lý khách hàng
        Route::get('/khach-hang', [KhachHangController::class, 'index'])->name('admin.khachhang.index');
        Route::get('/khach-hang/check/{maNguoiDung}', [KhachHangController::class, 'checkUser'])->name('admin.khachhang.checkUser');
        Route::post('/khach-hang', [KhachHangController::class, 'store'])->name('admin.khachhang.store');
        Route::put('/khach-hang/{id}', [KhachHangController::class, 'update'])->name('admin.khachhang.update');
        Route::delete('/khach-hang/{id}', [KhachHangController::class, 'destroy'])->name('admin.khachhang.destroy');

        // Quản lý nhân viên
        Route::get('/nhanvien', [NhanVienController::class, 'index'])->name('admin.nhanvien.index');
        Route::get('/nhanvien/check/{maNguoiDung}', [NhanVienController::class, 'checkMaNguoiDung'])->name('admin.nhanvien.check');
        Route::post('/nhanvien', [NhanVienController::class, 'store'])->name('admin.nhanvien.store');
        Route::get('/nhanvien/{id}/edit', [NhanVienController::class, 'edit'])->name('admin.nhanvien.edit');
        Route::put('/nhanvien/{id}', [NhanVienController::class, 'update'])->name('admin.nhanvien.update');
        Route::delete('/nhanvien/{id}', [NhanVienController::class, 'destroy'])->name('admin.nhanvien.destroy');

        // Kiểm tra vé sắp chiếu
        Route::get('/kiem-tra-ve-sap-chieu', [KiemTraVeSapChieuController::class, 'index'])->name('admin.kiemtra.index');
        Route::get('/kiem-tra-ve-sap-chieu/danh-sach', [KiemTraVeSapChieuController::class, 'danhSachVeSapChieu'])->name('admin.kiemtra.danhsach');
        Route::get('/kiem-tra-ve-sap-chieu/thong-bao', [KiemTraVeSapChieuController::class, 'thongBaoVeSapChieu'])->name('admin.kiemtra.thongbao');
    });
});

// ========================= ROUTE USER (KHÔNG CÓ AUTH) =========================
Route::get('/home', [CustomerHomeController::class, 'index'])->name('home');
    Route::get('/home/{id}', [CustomerHomeController::class, 'show'])->name('home.show');
// Các route đặt vé (chọn phòng, chọn suất, chọn ghế, xác nhận vé) -> cần login
Route::middleware(['auth','role:user'])->group(function () {
    

     Route::get('/thong-bao', [ThongBaoController::class, 'index'])->name('thongbao.index');

    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    //danh sach ve da dat
    Route::get('/my-tickets', [CustomerVeController::class, 'index'])->name('user.myTickets');
    //sua thong tin nguoi dung
    Route::get('/profile/update-profile', function () {
        return view('CustomerUpdateProfile');
    })->name('user.showUpdateProfileForm');
    
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('user.updateProfile');
    //thay mat khau
    Route::get('/profile/change-password', function () {
        return view('CustomerChangePassword');
    })->name('user.showChangePasswordForm');
    Route::post('/profile/change-password', [UserController::class, 'changePassword'])->name('user.changePassword');
    //chon ghe, dat ve
    Route::get('/chon-ghe/{masuatchieu}', [CustomerGheController::class, 'index'])->name('customer.ghe.index');
    Route::post('/chon-ghe/{masuatchieu}', [CustomerGheController::class, 'chonGhe'])->name('customer.ghe.chon');
    Route::get('/ve/confirm', [CustomerVeController::class, 'confirm'])->name('ve.confirm');
    Route::post('/ve/book', [CustomerVeController::class, 'bookTicket'])->name('ve.book');
    Route::get('/ve/{maHoaDon}', [CustomerVeController::class, 'show'])->name('ve.detail');

   
});

// ========================= ROUTE TEST DATABASE =========================
Route::get('/test-db', function () {
    try {
        $connName = DB::getDefaultConnection();
        $dbName = DB::connection()->getDatabaseName();
        $driver = DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME);
        $now = DB::select('SELECT NOW() as now_time')[0]->now_time ?? null;

        $tablesRaw = DB::select('SHOW TABLES');
        $tables = array_map(function ($t) {
            $a = (array) $t;
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
            'information_schema_lookup' => array_map(fn($r) => (array) $r, $info),
        ];
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
    }
});