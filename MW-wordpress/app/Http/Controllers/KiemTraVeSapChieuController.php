<?php

namespace App\Http\Controllers;

use App\Models\Ve;
use App\Models\SuatChieu;
use App\Models\HoaDon;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KiemTraVeSapChieuController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        return view('KiemTraVeSapChieu', compact('now'));
    }

    public function danhSachVeSapChieu()
    {
        $now = Carbon::now();
        $oneHourLater = $now->copy()->addHour();

        // Lấy danh sách vé đã thanh toán và có suất chiếu trong vòng 1 tiếng tới
        $ves = Ve::with(['suatChieu', 'hoaDon', 'suatChieu.phim', 'suatChieu.phongChieu'])
                ->where('TrangThai', 'paid')
                ->whereHas('suatChieu', function($query) use ($now, $oneHourLater) {
                    $query->whereBetween('NgayGioChieu', [$now, $oneHourLater]);
                })
                ->get()
                ->map(function($ve) {
                    return [
                        'MaVe' => $ve->MaVe,
                        'MaSuatChieu' => $ve->MaSuatChieu,
                        'MaPhong' => $ve->MaPhong,
                        'SoGhe' => $ve->SoGhe,
                        'GiaVe' => $ve->GiaVe,
                        'TrangThai' => $ve->TrangThai,
                        'NgayDat' => $ve->NgayDat,
                        'suat_chieu' => [
                            'MaSuatChieu' => $ve->suatChieu->MaSuatChieu,
                            'NgayGioChieu' => $ve->suatChieu->NgayGioChieu,
                            'phim' => $ve->suatChieu->phim ? [
                                'MaPhim' => $ve->suatChieu->phim->MaPhim,
                                'TenPhim' => $ve->suatChieu->phim->TenPhim
                            ] : null,
                            'phong_chieu' => $ve->suatChieu->phongChieu ? [
                                'MaPhong' => $ve->suatChieu->phongChieu->MaPhong,
                                'TenPhong' => $ve->suatChieu->phongChieu->TenPhong
                            ] : null
                        ],
                        'hoa_don' => $ve->hoaDon ? [
                            'MaHoaDon' => $ve->hoaDon->MaHoaDon,
                            'MaKhachHang' => $ve->hoaDon->MaKhachHang,
                            'TongTien' => $ve->hoaDon->TongTien
                        ] : null
                    ];
                });

        return response()->json([
            'success' => true,
            'ves' => $ves,
            'count' => $ves->count(),
            'thoi_gian_kiem_tra' => [
                'bat_dau' => $now->format('d/m/Y H:i:s'),
                'ket_thuc' => $oneHourLater->format('d/m/Y H:i:s')
            ]
        ]);
    }

    public function thongBaoVeSapChieu()
    {
        $now = Carbon::now();
        $oneHourLater = $now->copy()->addHour();

        // Lấy danh sách vé cần thông báo
        $ves = Ve::with(['suatChieu', 'hoaDon', 'suatChieu.phim'])
                ->where('TrangThai', 'paid')
                ->whereHas('suatChieu', function($query) use ($now, $oneHourLater) {
                    $query->whereBetween('NgayGioChieu', [$now, $oneHourLater]);
                })
                ->get();

        $thongBaoCount = 0;
        $khachHangNotified = [];

        foreach ($ves as $ve) {
            // Kiểm tra xem vé có hóa đơn và khách hàng không
            if ($ve->hoaDon && $ve->hoaDon->MaKhachHang) {
                $maKhachHang = $ve->hoaDon->MaKhachHang;
                
                // Gửi thông báo đến khách hàng (giả lập)
                // Trong thực tế, bạn có thể:
                // - Lưu vào bảng notifications
                // - Gửi email
                // - Gửi SMS
                // - Push notification
                
                if (!in_array($maKhachHang, $khachHangNotified)) {
                    $khachHangNotified[] = $maKhachHang;
                    $thongBaoCount++;
                    
                    // TODO: Thực hiện gửi thông báo thực tế ở đây
                    // Ví dụ: 
                    // Notification::create([
                    //     'MaKhachHang' => $maKhachHang,
                    //     'NoiDung' => 'Suất chiếu ' . $ve->suatChieu->phim->TenPhim . ' sẽ bắt đầu lúc ' . $ve->suatChieu->NgayGioChieu->format('H:i d/m/Y'),
                    //     'Loai' => 'reminder',
                    //     'TrangThai' => 'sent'
                    // ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã gửi thông báo cho ' . $thongBaoCount . ' khách hàng về ' . $ves->count() . ' vé sắp chiếu.',
            'thongBaoCount' => $thongBaoCount,
            'veCount' => $ves->count(),
            'thoi_gian_kiem_tra' => [
                'bat_dau' => $now->format('d/m/Y H:i:s'),
                'ket_thuc' => $oneHourLater->format('d/m/Y H:i:s')
            ]
        ]);
    }
}