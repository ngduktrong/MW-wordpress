<?php

namespace App\Http\Controllers;

use App\Models\NhanVien;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NhanVienController extends BaseCrudController
{
    protected $model = NhanVien::class;
    protected $primaryKey = 'MaNguoiDung';

    public function index()
    {
        $nhanViens = NhanVien::with('nguoiDung')->get();
        return view('AdminNhanVien', compact('nhanViens'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'MaNguoiDung' => 'required|exists:NguoiDung,MaNguoiDung|unique:NhanVien,MaNguoiDung',
            'ChucVu' => 'required|string|max:50',
            'Luong' => 'required|numeric|min:0',
            'VaiTro' => 'required|in:Admin,QuanLy,ThuNgan,BanVe',
        ]);

        try {
            DB::transaction(function() use ($data) {
                // Tạo nhân viên
                NhanVien::create($data);
                
                // Cập nhật LoaiNguoiDung thành NhanVien
                NguoiDung::where('MaNguoiDung', $data['MaNguoiDung'])
                         ->update(['LoaiNguoiDung' => 'NhanVien']);
            });

            return response()->json(['success' => 'Thêm nhân viên thành công!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi khi thêm nhân viên: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'ChucVu' => 'required|string|max:50',
            'Luong' => 'required|numeric|min:0',
            'VaiTro' => 'required|in:Admin,QuanLy,ThuNgan,BanVe',
        ]);

        try {
            $nhanVien = NhanVien::findOrFail($id);
            $nhanVien->update($data);

            return response()->json(['success' => 'Cập nhật nhân viên thành công!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi khi cập nhật nhân viên: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function() use ($id) {
                $nhanVien = NhanVien::findOrFail($id);
                
                // Xóa nhân viên
                $nhanVien->delete();
                
                // Chuyển LoaiNguoiDung về KhachHang
                NguoiDung::where('MaNguoiDung', $id)
                         ->update(['LoaiNguoiDung' => 'KhachHang']);
            });

            return response()->json(['success' => 'Xóa nhân viên thành công!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi khi xóa nhân viên: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        try {
            $nhanVien = NhanVien::with('nguoiDung')->findOrFail($id);
            return response()->json($nhanVien);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không tìm thấy nhân viên'], 404);
        }
    }

    public function checkMaNguoiDung($maNguoiDung)
    {
        try {
            $exists = NguoiDung::where('MaNguoiDung', $maNguoiDung)->exists();
            $isAlreadyEmployee = NhanVien::where('MaNguoiDung', $maNguoiDung)->exists();
            
            return response()->json([
                'exists' => $exists,
                'isAlreadyEmployee' => $isAlreadyEmployee
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'exists' => false,
                'isAlreadyEmployee' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}