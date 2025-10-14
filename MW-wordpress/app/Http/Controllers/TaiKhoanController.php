<?php

namespace App\Http\Controllers;

use App\Models\TaiKhoan;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class TaiKhoanController extends Controller
{
    // Hiển thị trang quản trị (list + form)
    public function adminIndex(Request $request)
    {
        // lấy danh sách tài khoản (paginate nếu cần)
        $taiKhoans = TaiKhoan::with('nguoiDung')->orderBy('TenDangNhap')->get();

        // lấy danh sách user (NguoiDung) để so sánh (nếu muốn hiển thị)
        $nguoiDungs = NguoiDung::orderBy('MaNguoiDung')->get();

        return view('AdminTaiKhoan', [
            'taiKhoans' => $taiKhoans,
            'nguoiDungs' => $nguoiDungs,
        ]);
    }

    // Trả về view data của 1 tài khoản để populate form edit (AJAX)
    public function edit($tenDangNhap)
    {
        $tk = TaiKhoan::findOrFail($tenDangNhap);
        return response()->json([
            'success' => true,
            'data' => $tk,
        ]);
    }

    // Tạo mới
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'TenDangNhap' => 'required|string|max:50|unique:TaiKhoan,TenDangNhap',
            'MatKhau' => 'required|string|min:6',
            'LoaiTaiKhoan' => ['required', Rule::in(['admin','user'])],
            'MaNguoiDung' => 'required|integer|exists:NguoiDung,MaNguoiDung|unique:TaiKhoan,MaNguoiDung',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // tạo bản ghi — model hash mật khẩu qua mutator
            $tk = TaiKhoan::create([
                'TenDangNhap' => $data['TenDangNhap'],
                'MatKhau' => Hash::make($data['MatKhau']),
                'LoaiTaiKhoan' => $data['LoaiTaiKhoan'],
                'MaNguoiDung' => $data['MaNguoiDung'] ?? null,
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true, 
                    'data' => $tk,
                    'message' => 'Tạo tài khoản thành công!' // THÊM THÔNG BÁO
                ], 201);
            }
            return redirect()->route('admin.taikhoan.index')->with('success', 'Tạo tài khoản thành công.');
        } catch (\Exception $e) {
            Log::error('TaiKhoan store error: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Lỗi khi tạo tài khoản.'
                ], 500);
            }
            return redirect()->back()->with('error', 'Lỗi khi tạo tài khoản.')->withInput();
        }
    }

    // Cập nhật
    public function update(Request $request, $tenDangNhap)
    {
        $tk = TaiKhoan::findOrFail($tenDangNhap);

        $data = $request->all();

        $rules = [
            'MatKhau' => 'nullable|string|min:6',
            'LoaiTaiKhoan' => ['required', Rule::in(['admin','user'])],
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Nếu MatKhau rỗng -> giữ nguyên mật khẩu hiện tại
            $updateData = [
                'LoaiTaiKhoan' => $data['LoaiTaiKhoan'],
            ];

            if (!empty($data['MatKhau'])) {
                $updateData['MatKhau'] = Hash::make($data['MatKhau']); 
            }

            $tk->update($updateData);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true, 
                    'data' => $tk,
                    'message' => 'Cập nhật tài khoản thành công!' // THÊM THÔNG BÁO
                ]);
            }
            return redirect()->route('admin.taikhoan.index')->with('success', 'Cập nhật thành công.');
        } catch (\Exception $e) {
            Log::error('TaiKhoan update error: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Lỗi khi cập nhật tài khoản.'
                ], 500);
            }
            return redirect()->back()->with('error', 'Lỗi khi cập nhật tài khoản.');
        }
    }

    // Xoá
    public function destroy(Request $request, $tenDangNhap)
    {
        try {
            $tk = TaiKhoan::findOrFail($tenDangNhap);
            $tk->delete();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Xóa tài khoản thành công!' // THÊM THÔNG BÁO
                ]);
            }
            return redirect()->route('admin.taikhoan.index')->with('success', 'Xoá tài khoản thành công.');
        } catch (\Exception $e) {
            Log::error('TaiKhoan destroy error: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Lỗi khi xoá tài khoản.'
                ], 500);
            }
            return redirect()->back()->with('error', 'Lỗi khi xoá tài khoản.');
        }
    }

    // API: trả về danh sách NguoiDung chưa có tài khoản
    public function getUsersWithoutAccounts()
    {
        // lấy MaNguoiDung từ NguoiDung mà chưa có trong TaiKhoan
        $users = NguoiDung::whereNotIn('MaNguoiDung', function($query) {
            $query->select('MaNguoiDung')->from('TaiKhoan')->whereNotNull('MaNguoiDung');
        })->get();

        return response()->json(['success' => true, 'data' => $users]);
    }
}