<?php

namespace App\Http\Controllers;

use App\Models\NhanVien;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class NhanVienController extends Controller
{
    /**
     * Danh sách nhân viên
     */
    public function index()
    {
        $data = NhanVien::with('nguoiDung')->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    /**
     * Tạo nhân viên mới
     * - Bắt buộc MaNguoiDung tồn tại ở bảng NguoiDung
     * - Không cho phép MaNguoiDung trùng ở NhanVien
     */
    public function store(Request $request)
    {
        $input = $request->only(['MaNguoiDung', 'ChucVu', 'Luong', 'VaiTro']);
        // trim nếu có
        if (isset($input['MaNguoiDung'])) {
            $input['MaNguoiDung'] = trim($input['MaNguoiDung']);
        }

        $rules = [
            'MaNguoiDung' => [
                'required',
                // nếu mã thực sự là chuỗi (ví dụ NV001) bỏ integer
                'integer',
                Rule::exists('NguoiDung', 'MaNguoiDung'),
                Rule::unique('NhanVien', 'MaNguoiDung')
            ],
            'ChucVu' => 'nullable|string|max:255',
            'Luong' => 'nullable|numeric',
            'VaiTro' => 'nullable|string|max:255',
        ];

        $messages = [
            'MaNguoiDung.required' => 'Bạn chưa nhập Mã Người Dùng.',
            'MaNguoiDung.integer' => 'Mã Người Dùng phải là số (nếu dùng chuỗi, chỉnh lại rule).',
            'MaNguoiDung.exists' => 'Mã Người Dùng không tồn tại trong hệ thống (bảng Người Dùng).',
            'MaNguoiDung.unique' => 'Mã Người Dùng đã được gán cho nhân viên khác.',
        ];

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $nv = NhanVien::create($validator->validated());
            return response()->json(['success' => true, 'data' => $nv], 201);
        } catch (\Exception $e) {
            Log::error('Error creating NhanVien: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi khi tạo nhân viên'], 500);
        }
    }

    /**
     * Cập nhật nhân viên
     * - ID ở đây là MaNguoiDung (khóa chính của bảng NhanVien)
     */
    public function update(Request $request, $id)
    {
        $input = $request->only(['ChucVu', 'Luong', 'VaiTro']);

        $rules = [
            'ChucVu' => 'nullable|string|max:255',
            'Luong' => 'nullable|numeric',
            'VaiTro' => 'nullable|string|max:255',
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $nv = NhanVien::find($id);
            if (!$nv) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy nhân viên.'], 404);
            }
            $nv->update($validator->validated());
            return response()->json(['success' => true, 'data' => $nv]);
        } catch (\Exception $e) {
            Log::error('Error updating NhanVien: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi khi cập nhật nhân viên'], 500);
        }
    }

    /**
     * Xóa nhân viên
     */
    public function destroy($id)
    {
        try {
            $nv = NhanVien::find($id);
            if (!$nv) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy nhân viên.'], 404);
            }
            $nv->delete();
            return response()->json(['success' => true, 'message' => 'Đã xóa.']);
        } catch (\Exception $e) {
            Log::error('Error deleting NhanVien: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi khi xóa nhân viên'], 500);
        }
    }

    /**
     * Lấy danh sách Người Dùng chưa có nhân viên (hữu ích cho tạo)
     */
    public function getNguoiDungChuaCoTaiKhoan()
    {
        try {
            $nguoiDungDaCoNhanVien = NhanVien::pluck('MaNguoiDung')->toArray();
            $nguoiDungChuaCo = NguoiDung::whereNotIn('MaNguoiDung', $nguoiDungDaCoNhanVien)
                                        ->with('taiKhoan')
                                        ->get();
            return response()->json(['success' => true, 'data' => $nguoiDungChuaCo]);
        } catch (\Exception $e) {
            Log::error('Error getting nguoi dung: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi khi tải danh sách người dùng'], 500);
        }
    }
}
