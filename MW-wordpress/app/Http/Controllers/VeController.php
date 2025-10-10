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
    try {
        \Log::info('Ve Store Request:', $request->all());
        
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

        $veData = [
            'MaSuatChieu' => $request->MaSuatChieu,
            'MaPhong' => $request->MaPhong,
            'SoGhe' => $request->SoGhe,
            'GiaVe' => $request->GiaVe,
            'TrangThai' => 'pending',
            'NgayDat' => null
        ];

        if ($request->has('MaHoaDon') && !empty($request->MaHoaDon)) {
            $veData['MaHoaDon'] = $request->MaHoaDon;
        }

        \Log::info('Ve Data to create:', $veData);

        $ve = Ve::create($veData);

        return response()->json([
            'success' => true,
            'message' => 'Vé đã được tạo thành công',
            've' => $ve
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation Error:', $e->errors());
        return response()->json([
            'success' => false,
            'message' => 'Lỗi validate dữ liệu',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Ve Store Error:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return response()->json([
            'success' => false,
            'message' => 'Lỗi server: ' . $e->getMessage()
        ], 500);
    }
}


    public function show($id)
    {
        $ve = Ve::with(['hoaDon', 'suatChieu', 'phongChieu', 'ghe'])
                ->findOrFail($id);
        
        return response()->json($ve);
    }

    public function update(Request $request, $id)
    {
    return response()->json([
        'success' => false,
        'message' => 'Chức năng sửa vé đã bị vô hiệu hóa'
    ], 405);
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