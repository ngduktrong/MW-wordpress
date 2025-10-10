<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\KhachHang;
use App\Models\NguoiDung;
use App\Models\TaiKhoan;
use App\Models\NhanVien;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class CustomerAuthController extends Controller
{
    //dang nhap
    public function login(Request $request){
    $request->validate([
        'ten_dang_nhap' => 'required|string',
        'mat_khau' => 'required|string'
    ]);
    
    if (Auth::guard('web')->attempt([
        'TenDangNhap' => $request->ten_dang_nhap,
        'password' => $request->mat_khau,
    ])) {
        return redirect()->intended(route('home'))->with('success', 'Đăng nhập thành công');
    }

    return back()->with('error','Sai tài khoản hoặc mật khẩu!');
}
    //dang ky
    public function register(Request $request){
        $request->validate([
            'ho_ten'=>['required','string','max:255'],
            'email'=> 'required|string|max:255|unique:nguoidung,Email',
            'mat_khau'=>'required|string|min:6',
            'so_dien_thoai'=>'required|string|max:15|unique:nguoidung,SoDienThoai',
            
            'ten_dang_nhap'=>'required|string|max:50|unique:taikhoan,TenDangNhap'
        ]);

        $user = NguoiDung::create([
            'HoTen'=>$request->ho_ten,
            'Email'=>$request->email,
            'SoDienThoai'=>$request->so_dien_thoai,
            'LoaiNguoiDung'=> NguoiDung::LOAI_KHACHHANG
        ]);
        //tao tai khoan
        TaiKhoan::create([
            'TenDangNhap'=>$request->ten_dang_nhap,
            'MatKhau'=>Hash::make($request->mat_khau),
            'LoaiTaiKhoan'=>TaiKhoan::USER,
            'MaNguoiDung'=>$user->MaNguoiDung
        ]);
        
        KhachHang::create([
             'MaNguoiDung'=>$user->MaNguoiDung,
             'DiemTichLuy'=>0,
            ]);
        return redirect()->route('login')->with('success','Đăng ký thành công, vui lòng đăng nhập');

        
       
        
    }

}