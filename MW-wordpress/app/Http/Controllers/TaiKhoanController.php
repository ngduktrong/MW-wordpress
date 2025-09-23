<?php

namespace App\Http\Controllers;

use App\Models\TaiKhoan;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TaiKhoanController extends Controller
{
    /**
     * Hiển thị trang admin quản lý tài khoản
     * Route: GET /admin/taikhoan
     */
    public function adminIndex()
    {
        $taiKhoans = TaiKhoan::with('nguoiDung')->orderBy('TenDangNhap', 'asc')->get();
        return view('AdminTaiKhoan', compact('taiKhoans'));
    }

    /**
     * Tạo mới tài khoản
     * Route: POST /admin/taikhoan
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'TenDangNhap'  => 'required|string|max:50|unique:TaiKhoan,TenDangNhap',
                'MatKhau'      => 'required|string|min:6',
                'MaNguoiDung'  => 'required|exists:NguoiDung,MaNguoiDung|unique:TaiKhoan,MaNguoiDung',
            ]);

            // Lấy thông tin người dùng để xác định LoaiTaiKhoan
            $nguoiDung = NguoiDung::findOrFail($data['MaNguoiDung']);
            $loaiTaiKhoan = $nguoiDung->LoaiNguoiDung === 'NhanVien' ? 'Admin' : 'User';

            TaiKhoan::create([
                'TenDangNhap' => $data['TenDangNhap'],
                'MatKhau' => Hash::make($data['MatKhau']),
                'LoaiTaiKhoan' => $loaiTaiKhoan,
                'MaNguoiDung' => $data['MaNguoiDung'],
            ]);

            return redirect()->route('admin.taikhoan.index')->with('success', 'Tạo tài khoản thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi khi tạo tài khoản: ' . $e->getMessage());
        }
    }

    /**
     * Lấy thông tin 1 tài khoản để edit (AJAX)
     * Route: GET /admin/taikhoan/{id}/edit
     */
    public function getTaiKhoan($id)
    {
        try {
            $taiKhoan = TaiKhoan::with('nguoiDung')->findOrFail($id);
            return response()->json($taiKhoan);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không tìm thấy tài khoản'], 404);
        }
    }

    /**
     * Cập nhật tài khoản
     * Route: PUT /admin/taikhoan/{id}
     */
    public function update(Request $request, $id)
    {
        try {
            $taiKhoan = TaiKhoan::findOrFail($id);

            $data = $request->validate([
                'MatKhau' => 'nullable|string|min:6',
                'MaNguoiDung' => ['required', 'exists:NguoiDung,MaNguoiDung', Rule::unique('TaiKhoan', 'MaNguoiDung')->ignore($taiKhoan->TenDangNhap, 'TenDangNhap')],
            ]);

            // Lấy thông tin người dùng để xác định LoaiTaiKhoan
            $nguoiDung = NguoiDung::findOrFail($data['MaNguoiDung']);
            $loaiTaiKhoan = $nguoiDung->LoaiNguoiDung === 'NhanVien' ? 'Admin' : 'User';

            // Cập nhật thông tin
            $taiKhoan->LoaiTaiKhoan = $loaiTaiKhoan;
            $taiKhoan->MaNguoiDung = $data['MaNguoiDung'];

            // Nếu có mật khẩu mới
            if (!empty($data['MatKhau'])) {
                $taiKhoan->MatKhau = Hash::make($data['MatKhau']);
            }

            $taiKhoan->save();

            return redirect()->route('admin.taikhoan.index')->with('success', 'Cập nhật tài khoản thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi khi cập nhật tài khoản: ' . $e->getMessage());
        }
    }

    /**
     * Xóa tài khoản
     * Route: DELETE /admin/taikhoan/{id}
     */
    public function destroy($id)
    {
        try {
            $taiKhoan = TaiKhoan::findOrFail($id);
            $taiKhoan->delete();

            return redirect()->route('admin.taikhoan.index')->with('success', 'Xóa tài khoản thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi khi xóa tài khoản: ' . $e->getMessage());
        }
    }

    /**
     * Kiểm tra mã người dùng
     */
    public function checkMaNguoiDung($maNguoiDung)
    {
        try {
            $nguoiDung = NguoiDung::find($maNguoiDung);
            
            if (!$nguoiDung) {
                return response()->json(['exists' => false]);
            }

            // Kiểm tra xem mã người dùng đã có tài khoản chưa
            $hasAccount = TaiKhoan::where('MaNguoiDung', $maNguoiDung)->exists();

            return response()->json([
                'exists' => true,
                'nguoiDung' => $nguoiDung,
                'hasAccount' => $hasAccount
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi khi kiểm tra mã người dùng'], 500);
        }
    }
}