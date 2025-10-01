<?php

namespace App\Http\Controllers;

use App\Models\HoaDon;
use App\Models\Ve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HoaDonController extends Controller
{
    // 1. CRUD Operations
    public function index()
    {
        $hoadons = HoaDon::with(['nhanVien', 'khachHang', 'ves'])
                        ->orderBy('NgayLap', 'desc')
                        ->get();
        
        return view('AdminHoaDon', compact('hoadons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'MaNhanVien' => 'nullable|integer|exists:NhanVien,MaNguoiDung',
            'MaKhachHang' => 'nullable|integer|exists:KhachHang,MaNguoiDung',
            'TongTien' => 'required|numeric|min:0',
        ]);

        $hoadon = HoaDon::create($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Hóa đơn đã được tạo thành công',
            'MaHoaDon' => $hoadon->MaHoaDon
        ], 201);
    }

    public function show($id)
    {
        $hoadon = HoaDon::with(['nhanVien', 'khachHang', 'ves.suatChieu', 'ves.phongChieu'])
                        ->findOrFail($id);
        
        return response()->json($hoadon);
    }

    public function update(Request $request, $id)
    {
        $hoadon = HoaDon::findOrFail($id);
        
        $request->validate([
            'MaNhanVien' => 'nullable|integer|exists:NhanVien,MaNguoiDung',
            'MaKhachHang' => 'nullable|integer|exists:KhachHang,MaNguoiDung',
            'TongTien' => 'required|numeric|min:0',
        ]);

        $hoadon->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Hóa đơn đã được cập nhật'
        ]);
    }

    public function destroy($id)
    {
        $hoadon = HoaDon::findOrFail($id);
        
        // Kiểm tra nếu hóa đơn có vé đã thanh toán thì không cho xóa
        $hasPaidVe = $hoadon->ves()->where('TrangThai', 'paid')->exists();
        if ($hasPaidVe) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa hóa đơn đã có vé thanh toán'
            ], 422);
        }

        $hoadon->ves()->update(['MaHoaDon' => null]);
        $hoadon->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Hóa đơn đã được xóa'
        ]);
    }

    // 2. Search & Filter
    public function getByMaKhachHang($maKhachHang)
    {
        $hoadons = HoaDon::with(['nhanVien', 'khachHang', 'ves'])
                        ->where('MaKhachHang', $maKhachHang)
                        ->orderBy('NgayLap', 'desc')
                        ->get();
        
        return response()->json($hoadons);
    }

    public function getByNgayLap($ngay)
    {
        $hoadons = HoaDon::with(['nhanVien', 'khachHang', 'ves'])
                        ->whereDate('NgayLap', $ngay)
                        ->orderBy('NgayLap', 'desc')
                        ->get();
        
        return response()->json($hoadons);
    }

    public function getByKhoangNgay(Request $request)
    {
        $request->validate([
            'tuNgay' => 'required|date',
            'denNgay' => 'required|date|after_or_equal:tuNgay'
        ]);

        $hoadons = HoaDon::with(['nhanVien', 'khachHang', 'ves'])
                        ->whereBetween('NgayLap', [$request->tuNgay, $request->denNgay])
                        ->orderBy('NgayLap', 'desc')
                        ->get();
        
        return response()->json($hoadons);
    }

    // 3. Revenue Statistics
    public function getTongDoanhThuTheoNgay($ngay)
    {
        $tongDoanhThu = HoaDon::whereDate('NgayLap', $ngay)
                            ->sum('TongTien');
        
        return response()->json([
            'ngay' => $ngay,
            'tongDoanhThu' => $tongDoanhThu
        ]);
    }

    public function getTongDoanhThuTheoKhoangNgay(Request $request)
    {
        $request->validate([
            'tuNgay' => 'required|date',
            'denNgay' => 'required|date|after_or_equal:tuNgay'
        ]);

        $tongDoanhThu = HoaDon::whereBetween('NgayLap', [$request->tuNgay, $request->denNgay])
                            ->sum('TongTien');
        
        return response()->json([
            'tuNgay' => $request->tuNgay,
            'denNgay' => $request->denNgay,
            'tongDoanhThu' => $tongDoanhThu
        ]);
    }

    // 4. Data Sync
    public function capNhatNgayLapTuVe($maHoaDon)
    {
        $hoadon = HoaDon::findOrFail($maHoaDon);
        
        // Lấy ngày đặt vé gần nhất từ các vé đã thanh toán
        $ngayDatVe = $hoadon->ves()
                           ->where('TrangThai', 'paid')
                           ->orderBy('NgayDat', 'desc')
                           ->value('NgayDat');
        
        if ($ngayDatVe) {
            $hoadon->update(['NgayLap' => $ngayDatVe]);
            
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật ngày lập từ vé'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy vé thanh toán để cập nhật'
        ], 422);
    }
}