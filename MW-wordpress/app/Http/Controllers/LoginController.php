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
            'LoaiTaiKhoan' => 'required|in:admin,user'
        ]);

        $taiKhoan = TaiKhoan::where('TenDangNhap', $request->TenDangNhap)
                           ->where('LoaiTaiKhoan', $request->LoaiTaiKhoan)
                           ->first();

        if ($taiKhoan && Hash::check($request->MatKhau, $taiKhoan->MatKhau)) {
            Auth::login($taiKhoan);
            
            if ($request->LoaiTaiKhoan === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('admin.phim');
            }
        }

        return back()->withErrors([
            'TenDangNhap' => 'Thông tin đăng nhập không chính xác.',
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