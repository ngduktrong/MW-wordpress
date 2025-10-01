<?php

namespace App\Http\Controllers;

use App\Models\Ve;
use App\Models\HoaDon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VeController extends Controller
{
    // 1. CRUD Operations
    public function index()
    {
        $ves = Ve::with(['hoaDon', 'suatChieu', 'phongChieu'])
                ->orderBy('MaVe', 'desc')
                ->get();
        
        return view('AdminVe', compact('ves'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'MaSuatChieu' => 'required|integer|exists:SuatChieu,MaSuatChieu',
            'MaPhong' => 'required|integer|exists:PhongChieu,MaPhong',
            'SoGhe' => 'required|string|max:5',
            'MaHoaDon' => 'nullable|integer|exists:HoaDon,MaHoaDon',
            'GiaVe' => 'required|numeric|min:0',
        ]);

        // Kiểm tra trùng ghế
        $veTrung = Ve::where('MaSuatChieu', $request->MaSuatChieu)
                    ->where('SoGhe', $request->SoGhe)
                    ->exists();
        
        if ($veTrung) {
            return response()->json([
                'success' => false,
                'message' => 'Ghế đã được đặt cho suất chiếu này'
            ], 422);
        }

        $ve = Ve::create([
            'MaSuatChieu' => $request->MaSuatChieu,
            'MaPhong' => $request->MaPhong,
            'SoGhe' => $request->SoGhe,
            'MaHoaDon' => $request->MaHoaDon,
            'GiaVe' => $request->GiaVe,
            'TrangThai' => 'pending', // Mặc định "Chưa thanh toán"
            'NgayDat' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Vé đã được tạo thành công',
            've' => $ve
        ], 201);
    }

    public function show($id)
    {
        $ve = Ve::with(['hoaDon', 'suatChieu', 'phongChieu', 'ghe'])
                ->findOrFail($id);
        
        return response()->json($ve);
    }

    public function update(Request $request, $id)
    {
        $ve = Ve::findOrFail($id);
        
        // Không cho sửa nếu vé đã thanh toán
        if ($ve->TrangThai === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Không thể sửa vé đã thanh toán'
            ], 422);
        }

        $request->validate([
            'MaSuatChieu' => 'required|integer|exists:SuatChieu,MaSuatChieu',
            'MaPhong' => 'required|integer|exists:PhongChieu,MaPhong',
            'SoGhe' => 'required|string|max:5',
            'MaHoaDon' => 'nullable|integer|exists:HoaDon,MaHoaDon',
            'GiaVe' => 'required|numeric|min:0',
            'TrangThai' => 'required|in:available,booked,paid,cancelled,pending',
        ]);

        // Kiểm tra trùng ghế (trừ vé hiện tại)
        $veTrung = Ve::where('MaSuatChieu', $request->MaSuatChieu)
                    ->where('SoGhe', $request->SoGhe)
                    ->where('MaVe', '!=', $id)
                    ->exists();
        
        if ($veTrung) {
            return response()->json([
                'success' => false,
                'message' => 'Ghế đã được đặt cho suất chiếu này'
            ], 422);
        }

        $ve->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Vé đã được cập nhật'
        ]);
    }

    public function destroy($id)
    {
        $ve = Ve::findOrFail($id);
        
        // Không cho xóa nếu vé đã thanh toán
        if ($ve->TrangThai === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa vé đã thanh toán'
            ], 422);
        }

        $ve->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vé đã được xóa'
        ]);
    }

    public function getVesByIds(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer'
        ]);

        $ves = Ve::with(['hoaDon', 'suatChieu', 'phongChieu'])
                ->whereIn('MaVe', $request->ids)
                ->get();
        
        return response()->json($ves);
    }

    // 2. Payment & Status
    public function updateTrangThaiVeToPaid($id)
    {
        $ve = Ve::findOrFail($id);
        
        $ve->update([
            'TrangThai' => 'paid',
            'NgayDat' => now()
        ]);

        // Đồng bộ NgayLap cho hóa đơn liên quan
        if ($ve->MaHoaDon) {
            $hoadon = HoaDon::find($ve->MaHoaDon);
            if ($hoadon) {
                $hoadon->update(['NgayLap' => $ve->NgayDat]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Vé đã được thanh toán thành công'
        ]);
    }

    // 3. Search & Lookup
    public function getVeByMaHoaDon($maHoaDon)
    {
        $ves = Ve::with(['hoaDon', 'suatChieu', 'phongChieu'])
                ->where('MaHoaDon', $maHoaDon)
                ->get();
        
        return response()->json($ves);
    }

    public function getVeByMaKhachHang($maKhachHang)
    {
        $ves = Ve::with(['hoaDon', 'suatChieu', 'phongChieu'])
                ->whereHas('hoaDon', function($query) use ($maKhachHang) {
                    $query->where('MaKhachHang', $maKhachHang);
                })
                ->get();
        
        return response()->json($ves);
    }

    public function getSoGheDaDatBySuatChieu($maSuatChieu)
    {
        $soGhes = Ve::where('MaSuatChieu', $maSuatChieu)
                   ->whereIn('TrangThai', ['booked', 'paid', 'pending'])
                   ->pluck('SoGhe');
        
        return response()->json($soGhes);
    }

    // 4. Statistics & Reports
    public function getSoVeDaThanhToan()
    {
        $soVe = Ve::where('TrangThai', 'paid')->count();
        
        return response()->json([
            'soVeDaThanhToan' => $soVe
        ]);
    }
}