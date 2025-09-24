<?php

namespace App\Http\Controllers;

use App\Models\TaiKhoan;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaiKhoanController extends BaseCrudController
{
    protected $model = TaiKhoan::class;
    protected $primaryKey = 'TenDangNhap';

    /**
     * Display admin page with accounts
     */
    public function adminIndex()
    {
        $taiKhoans = TaiKhoan::with('nguoiDung')->orderBy('created_at', 'desc')->paginate(15);
        return view('AdminTaiKhoan', compact('taiKhoans'));
    }

    /**
     * Override store method to validate MaNguoiDung
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TenDangNhap' => 'required|string|max:50|unique:TaiKhoan,TenDangNhap',
            'MatKhau' => 'required|string|min:6',
            'LoaiTaiKhoan' => 'required|in:Admin,User',
            'MaNguoiDung' => 'required|exists:NguoiDung,MaNguoiDung',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi thêm tài khoản!');
        }

        // Check if MaNguoiDung already has an account
        $existingAccount = TaiKhoan::where('MaNguoiDung', $request->MaNguoiDung)->first();
        if ($existingAccount) {
            return redirect()->back()
                ->withErrors(['MaNguoiDung' => 'Mã người dùng này đã có tài khoản!'])
                ->withInput()
                ->with('error', 'Mã người dùng này đã có tài khoản!');
        }

        DB::transaction(function() use ($request) {
            TaiKhoan::create([
                'TenDangNhap' => $request->TenDangNhap,
                'MatKhau' => $request->MatKhau, // Password will be hashed by mutator
                'LoaiTaiKhoan' => $request->LoaiTaiKhoan,
                'MaNguoiDung' => $request->MaNguoiDung,
            ]);
        });

        return redirect()->route('admin.taikhoan.index')->with('success', 'Thêm tài khoản thành công!');
    }

    /**
     * Override update method
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'MatKhau' => 'sometimes|string|min:6',
            'LoaiTaiKhoan' => 'required|in:Admin,User',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật tài khoản!');
        }

        DB::transaction(function() use ($request, $id) {
            $taiKhoan = TaiKhoan::findOrFail($id);
            
            $updateData = [
                'LoaiTaiKhoan' => $request->LoaiTaiKhoan,
            ];

            // Only update password if provided
            if ($request->filled('MatKhau')) {
                $updateData['MatKhau'] = $request->MatKhau;
            }

            $taiKhoan->update($updateData);
        });

        return redirect()->route('admin.taikhoan.index')->with('success', 'Cập nhật tài khoản thành công!');
    }

    /**
     * Get account data for editing
     */
    public function getAccountData($id)
    {
        $taiKhoan = TaiKhoan::with('nguoiDung')->findOrFail($id);
        return response()->json($taiKhoan);
    }

    /**
     * Get users without accounts for dropdown
     */
    public function getUsersWithoutAccounts()
    {
        $users = NguoiDung::whereNotIn('MaNguoiDung', function($query) {
            $query->select('MaNguoiDung')->from('TaiKhoan')->whereNotNull('MaNguoiDung');
        })->get();

        return response()->json($users);
    }
}