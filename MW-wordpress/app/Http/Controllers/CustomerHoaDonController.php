<?php

namespace App\Http\Controllers;

use App\Models\HoaDon;
use App\Models\KhachHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerHoaDonController extends Controller
{
    /**
     * Hiển thị danh sách hóa đơn của khách hàng hiện tại
     */
    public function index()
    {
        $maNguoiDung = Auth::user()->MaNguoiDung;
        $khachHang = KhachHang::where('MaNguoiDung', $maNguoiDung)->first();

        if (!$khachHang) {
            return redirect()->route('home')->with('error', 'Không tìm thấy khách hàng.');
        }

        // lấy danh sách hóa đơn kèm vé, suất chiếu, phòng, phim
        $hoaDons = HoaDon::where('MaKhachHang', $khachHang->MaKhachHang)
                         ->with([
                             'ves.suatChieu.phim',
                             'ves.suatChieu.phongChieu', // đã sửa phong → phongChieu
                             'ves.ghe'
                         ])
                         ->orderByDesc('NgayLap')
                         ->get();

        return view('HoaDonIndex', compact('hoaDons'));
    }

    /**
     * Tạo hóa đơn mới (luôn gán MaNhanVien = 6)
     */
    public function store(Request $request)
{
    $maNguoiDung = Auth::user()->MaNguoiDung;

    // kiểm tra khách hàng tồn tại
    $khachHang = KhachHang::where('MaNguoiDung', $maNguoiDung)->first();

    if (!$khachHang) {
        return redirect()->route('home')->with('error', 'Không tìm thấy khách hàng.');
    }

    // ✅ Lưu ý: MaKhachHang = MaNguoiDung (vì HoaDon.MaKhachHang FK → KhachHang.MaNguoiDung)
    $hoaDon = HoaDon::create([
        'MaKhachHang' => $khachHang->MaNguoiDung, // 👈 đây mới là đúng
        'MaNhanVien'  => null,
        'NgayLap'     => now(),
        'TongTien'    => 0,
    ]);

    return redirect()->route('home')->with('success', 'Tạo hóa đơn thành công!');
}


    /**
     * Xem chi tiết hóa đơn
     */
    public function show($id)
    {
        $hoaDon = HoaDon::with([
                        'ves.suatChieu.phim',
                        'ves.suatChieu.phongChieu', // sửa lại đúng quan hệ
                        'ves.ghe'
                    ])->findOrFail($id);

        return view('HoaDonShow', compact('hoaDon'));
    }
}
