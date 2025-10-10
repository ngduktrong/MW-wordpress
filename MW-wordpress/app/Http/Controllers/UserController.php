<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NguoiDung;
use App\Models\TaiKhoan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    #xem profile cua khach hang
    public function profile(){
        $user  = Auth::user();
        $NguoiDung = NguoiDung::find($user->MaNguoiDung);
        return view('CustomerProfile',compact('user', 'NguoiDung'));
    }
    #cap nhat profile
    public function updateProfile(Request $request){
        $request->validate([
            'ho_ten'=> 'required|string|max:255',
            'email'=>'required|string|email|max:255|unique:NguoiDung,email',
            'so_dien_thoai'=>'required|string|max:15|unique:NguoiDung,SoDienThoai'
        ]);
        $user = Auth::user();
        $NguoiDung = NguoiDung::find($user->MaNguoiDung);

        $NguoiDung->update([
            'HoTen'=>$request->ho_ten,
            'Email'=>$request->email,
            'SoDienThoai'=>$request->so_dien_thoai,
        ]);
        return redirect()->back()->with('success','Cập nhật thành công');
    }
    #thay doi mat khau
    public function changePassword(Request $request){
        $request->validate([
            'current_password'=>'required',
            'new_password'=>'required|string|min:6|confirmed'
        ]);

        $taikhoan = Auth::user();
        if(!Hash::check($request->current_password,$taikhoan->MatKhau)){
            return redirect()->back()->with('error','Mật khẩu hiện tại không đúng');
        }
        $taikhoan->MatKhau = Hash::make($request->new_password);
        $taikhoan->save();
        return redirect()->back()->with('success','Đổi mật khẩu thành công');
    }
    
}
