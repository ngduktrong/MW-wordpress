<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Ve;
use App\Models\TaiKhoan;

class ThongBaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Hiển thị vé sắp chiếu cho user đang đăng nhập.
     */
    public function index(Request $request)
    {
        $taiKhoan = Auth::user();
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        // Kiểm tra xem có phải là chế độ thông báo không
        $isAlertMode = $request->has('alert') && $request->get('alert') == 'true';

        // Lấy MaNguoiDung từ tài khoản
        $maNguoiDung = $taiKhoan->MaNguoiDung ?? null;
        if (!$maNguoiDung) {
            return view('ThongBao', [
                'ves' => collect(),
                'isAlertMode' => $isAlertMode
            ]);
        }

        // Tên bảng Ve
        $veTable = (new Ve())->getTable();

        // Bắt đầu query: Ve (v) join HoaDon (h) join SuatChieu (s)
        $query = DB::table($veTable . ' as v')
            ->leftJoin('HoaDon as h', 'v.MaHoaDon', '=', 'h.MaHoaDon')
            ->leftJoin('SuatChieu as s', 'v.MaSuatChieu', '=', 's.MaSuatChieu');

        // Nếu có bảng Phim và SuatChieu.MaPhim thì join để lấy tên phim
        $joinPhim = false;
        if (Schema::hasTable('Phim') && Schema::hasColumn('SuatChieu', 'MaPhim')) {
            $query->leftJoin('Phim as p', 's.MaPhim', '=', 'p.MaPhim');
            $joinPhim = true;
        }

        // Lọc: chỉ vé thuộc hóa đơn của người dùng hiện tại
        if (Schema::hasColumn('HoaDon', 'MaKhachHang')) {
            $query->where('h.MaKhachHang', $maNguoiDung);
        } else {
            return view('ThongBao', [
                'ves' => collect(),
                'isAlertMode' => $isAlertMode
            ]);
        }

        // Lọc vé theo chế độ
        if ($isAlertMode) {
            // CHẾ ĐỘ THÔNG BÁO: chỉ vé sắp chiếu trong vòng 1 giờ tới
            $oneHourLater = $now->copy()->addHour();
            if (Schema::hasColumn('SuatChieu', 'NgayGioChieu')) {
                $query->whereBetween('s.NgayGioChieu', [$now, $oneHourLater]);
                $query->orderBy('s.NgayGioChieu', 'asc');
            }
        } else {
            // CHẾ ĐỘ THÔNG THƯỜNG: vé sắp chiếu trong tương lai
            if (Schema::hasColumn('SuatChieu', 'NgayGioChieu')) {
                $query->where('s.NgayGioChieu', '>', $now);
                $query->orderBy('s.NgayGioChieu', 'asc');
            } else {
                if (Schema::hasColumn($veTable, 'NgayDat')) {
                    $query->where('v.NgayDat', '>', $now);
                    $query->orderBy('v.NgayDat', 'asc');
                } else {
                    $primary = (new Ve())->getKeyName();
                    if (Schema::hasColumn($veTable, $primary)) {
                        $query->orderBy("v.$primary", 'asc');
                    }
                }
            }
        }

        // Chọn cột trả về (chuẩn hoá)
        $selects = [
            'v.MaVe as MaVe',
            'v.SoGhe as SoGhe',
            'v.MaPhong as MaPhong',
            'v.GiaVe as GiaVe',
            'v.TrangThai as TrangThai',
        ];
        if (Schema::hasColumn('SuatChieu', 'NgayGioChieu')) {
            $selects[] = 's.NgayGioChieu as NgayGioChieu';
        }
        if ($joinPhim && Schema::hasColumn('Phim', 'TenPhim')) {
            $selects[] = 'p.TenPhim as TenPhim';
        } else if (Schema::hasColumn('SuatChieu', 'MaPhim')) {
            $selects[] = 's.MaPhim as MaPhim';
        }

        $rows = $query->select($selects)
                      ->distinct()
                      ->get();

        // Chuẩn hoá sang cấu trúc đơn giản cho view
        $ves = $rows->map(function ($r) {
            $movie = $r->TenPhim ?? ($r->MaPhim ?? null);
            $showtime = $r->NgayGioChieu ?? null;
            $room = $r->MaPhong ?? null;
            $seat = $r->SoGhe ?? null;
            $code = $r->MaVe ?? null;

            return (object)[
                'movie'   => $movie,
                'showtime'=> $showtime,
                'room'    => $room,
                'seat'    => $seat,
                'code'    => $code,
            ];
        });

        return view('ThongBao', [
            'ves' => $ves,
            'isAlertMode' => $isAlertMode
        ]);
    }
}