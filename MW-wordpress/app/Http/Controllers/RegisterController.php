<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use App\Models\TaiKhoan;
use App\Models\KhachHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-zA-Z0-9_]+$/',
                'not_in:admin,administrator'
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'max:100'
            ],
            'confirmPassword' => [
                'required',
                'string',
                'same:password'
            ],
            'phone' => [
                'required',
                'string',
                'regex:/^[0-9]{10,11}$/'
            ],
            'email' => [
                'required',
                'email',
                'unique:NguoiDung,Email'
            ]
        ], [
            'username.required' => 'Tên đăng nhập không được để trống',
            'username.min' => 'Tên đăng nhập phải có ít nhất 3 ký tự',
            'username.max' => 'Tên đăng nhập không được vượt quá 50 ký tự',
            'username.regex' => 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới',
            'username.not_in' => 'Tên đăng nhập này không được phép sử dụng',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.max' => 'Mật khẩu không được vượt quá 100 ký tự',
            'confirmPassword.required' => 'Xác nhận mật khẩu không được để trống',
            'confirmPassword.same' => 'Mật khẩu xác nhận không khớp',
            'phone.required' => 'Số điện thoại không được để trống',
            'phone.regex' => 'Số điện thoại phải có 10-11 chữ số',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được sử dụng'
        ]);

        // Kiểm tra validation
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Vui lòng kiểm tra lại thông tin đăng ký');
        }

        $username = $request->input('username');
        $password = $request->input('password');
        $phone = $request->input('phone');
        $email = $request->input('email');

        // Kiểm tra tên đăng nhập đã tồn tại
        $existingUser = TaiKhoan::where('TenDangNhap', $username)->first();
        if ($existingUser) {
            return back()
                ->withInput()
                ->withErrors(['username' => 'Tên đăng nhập đã tồn tại'])
                ->with('error', 'Tên đăng nhập đã tồn tại');
        }

        // Kiểm tra số điện thoại đã tồn tại
        $existingPhone = NguoiDung::where('SoDienThoai', $phone)->first();
        if ($existingPhone) {
            return back()
                ->withInput()
                ->withErrors(['phone' => 'Số điện thoại đã được sử dụng'])
                ->with('error', 'Số điện thoại đã được sử dụng');
        }

        // Kiểm tra email đã tồn tại
        $existingEmail = NguoiDung::where('Email', $email)->first();
        if ($existingEmail) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Email đã được sử dụng'])
                ->with('error', 'Email đã được sử dụng');
        }

        // Bắt đầu transaction
        DB::beginTransaction();

        try {
            // Tạo người dùng mặc định
            $nguoiDung = NguoiDung::create([
                'HoTen' => $username,
                'SoDienThoai' => $phone,
                'Email' => $email,
                'LoaiNguoiDung' => 'KhachHang',
            ]);

            // Tạo khách hàng - SỬA: GÁN TRỰC TIẾP MaNguoiDung
            $khachHang = new KhachHang();
            $khachHang->MaNguoiDung = $nguoiDung->MaNguoiDung;
            $khachHang->DiemTichLuy = 0;
            $khachHang->save();

            // Tạo tài khoản mới
            TaiKhoan::create([
                'TenDangNhap' => $username,
                'MatKhau' => Hash::make($password),
                'LoaiTaiKhoan' => 'user',
                'MaNguoiDung' => $nguoiDung->MaNguoiDung,
            ]);

            DB::commit();

            return redirect()->route('login')
                ->with('success', 'Đăng ký thành công! Mời đăng nhập.')
                ->with('usernameInput', $username);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Registration error: ' . $e->getMessage());
            \Log::error('Registration trace: ' . $e->getTraceAsString());
            
            return back()
                ->withInput()
                ->with('error', 'Không thể đăng ký. Lỗi: ' . $e->getMessage());
        }
    }
}