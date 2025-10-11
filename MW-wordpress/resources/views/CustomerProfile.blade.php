@extends('layouts.app')

@section('content')
<style>
    body {
            background-image: url('/img/home-wallpaper.jpg');
            background-size: cover;
            background-position: center;
             height: (1100px);
            background-repeat: repeat;
            
        }
        .profile-container{
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;  
            /* height: 100vh; */
            
        }


        body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 0;
            pointer-events: none;
            height: auto;
            height: min(1100px);
        }

        .profile-container>* {
            position: relative;
            z-index: 2;
              
            
        }
    .profile-container {
        max-width: 500px;
        margin: 50px auto;
        margin-top: 250px;

        background: #f9f9f9;
        padding: 25px 30px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        font-family: 'Segoe UI', sans-serif;
    }

    .profile-container h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    .profile-info p {
        font-size: 16px;
        width: 400px;
        margin: 10px 0;
        color: #444;
        padding: 8px 12px;
        background: #fff;
        border-radius: 6px;
        border: 1px solid #ddd;
    }

    .profile-actions {
        margin-top: 25px;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .profile-actions a {
        flex: 1;
        text-align: center;
        background: #007bff;
        color: white;
        padding: 10px;
        border-radius: 6px;
        text-decoration: none;
        transition: 0.2s;
    }

    .profile-actions a:hover {
        background: #0056b3;
    }

    .profile-actions a:nth-child(1) {
        background: #6c757d;
    }

    .profile-actions a:nth-child(1):hover {
        background: #5a6268;
    }
</style>

<div class="profile-container">
    @include('layouts.nav')
    <h2>Thông tin cá nhân</h2>

    <div class="profile-info">
        <p><strong>Họ tên:</strong> {{ $NguoiDung->HoTen }}</p>
        <p><strong>Tên đăng nhập:</strong> {{ $NguoiDung->taikhoan->TenDangNhap }}</p>
        <p><strong>Email:</strong> {{ $NguoiDung->Email }}</p>
        <p><strong>Số điện thoại:</strong> {{ $NguoiDung->SoDienThoai }}</p>
    </div>

    <div class="profile-actions">
        <a href="{{ route('home') }}">🏠 Trang chủ</a>
        <a href="{{ route('user.showUpdateProfileForm') }}">✏️ Cập nhật</a>
        <a href="{{ route('user.showChangePasswordForm') }}">🔒 Đổi mật khẩu</a>
    </div>
</div>
@endsection
