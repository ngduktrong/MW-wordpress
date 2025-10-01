<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\TaiKhoan;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'TenDangNhap' => 'required',
            'MatKhau' => 'required',
            // ĐÃ XÓA: validation cho LoaiTaiKhoan
        ]);

        // CHỈ tìm theo tên đăng nhập, không tìm theo LoaiTaiKhoan nữa
        $taiKhoan = TaiKhoan::where('TenDangNhap', $request->TenDangNhap)->first();

        // Kiểm tra tài khoản tồn tại và mật khẩu đúng
        if ($taiKhoan && Hash::check($request->MatKhau, $taiKhoan->MatKhau)) {
            Auth::login($taiKhoan);
            
            // Chuyển hướng dựa vào LoaiTaiKhoan trong database
            if ($taiKhoan->LoaiTaiKhoan === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('admin.phim');
            }
        }

        return back()->withErrors([
            'TenDangNhap' => 'Tên đăng nhập hoặc mật khẩu không chính xác.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}