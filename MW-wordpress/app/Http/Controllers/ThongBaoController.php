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
    public function index()
    {
        $taiKhoan = Auth::user(); // instance của TaiKhoan
        $now = Carbon::now();

        // Lấy MaNguoiDung từ tài khoản (bắt buộc trên model TaiKhoan bạn có trường này)
        $maNguoiDung = $taiKhoan->MaNguoiDung ?? null;
        if (!$maNguoiDung) {
            // Nếu không có MaNguoiDung thì trả rỗng để an toàn
            return view('ThongBao', ['ves' => collect()]);
        }

        // Tên bảng Ve
        $veTable = (new Ve())->getTable(); // thường 'Ve'

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
        // Theo mô hình bạn cung cấp: HoaDon.MaKhachHang = KhachHang.MaNguoiDung (và TaiKhoan.MaNguoiDung là id người dùng)
        if (Schema::hasColumn('HoaDon', 'MaKhachHang')) {
            $query->where('h.MaKhachHang', $maNguoiDung);
        } else {
            // nếu không có cột MaKhachHang trong HoaDon thì trả rỗng để tránh leak dữ liệu
            return view('ThongBao', ['ves' => collect()]);
        }

        // Lọc chỉ vé sắp chiếu: SuatChieu.NgayGioChieu > now
        if (Schema::hasColumn('SuatChieu', 'NgayGioChieu')) {
            $query->where('s.NgayGioChieu', '>', $now);
            $query->orderBy('s.NgayGioChieu', 'asc');
        } else {
            // Nếu không có cột thời gian trên SuatChieu, thử dùng v.NgayDat
            if (Schema::hasColumn($veTable, 'NgayDat')) {
                $query->where('v.NgayDat', '>', $now);
                $query->orderBy('v.NgayDat', 'asc');
            } else {
                // fallback order by primary key MaVe nếu có
                $primary = (new Ve())->getKeyName(); // MaVe
                if (Schema::hasColumn($veTable, $primary)) {
                    $query->orderBy("v.$primary", 'asc');
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
            // nếu không join Phim thì vẫn lấy MaPhim để hiển thị nếu cần
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

        return view('ThongBao', ['ves' => $ves]);
    }
}
