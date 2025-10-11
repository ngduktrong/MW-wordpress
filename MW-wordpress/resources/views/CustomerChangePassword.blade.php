@extends('layouts.app')

@section('content')

<h2>Đổi mật khẩu</h2>

<style>
     body {
            background-image: url('/img/home-wallpaper.jpg');
            background-size: cover;
            background-position: center;
             height: (1100px);
            background-repeat: repeat;
            
        }
        .container{
            display: flex;
            justify-content: center;
            align-items: center;  
            height: 100vh;
            
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

        .container>* {
            position: relative;
            z-index: 2;
              
            
        }
        .changePassword-field {
            color: rgb(0, 0, 0);
            padding: 20px;
            width: 500px;
            padding: 30px;
            display: flex;
            flex-direction: column;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
        }
        .content-password {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }
         .confirmBtn {
            background-color: #1fb9d4;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 0px 0;
            cursor: pointer;
            border-radius: 25px;
            position: relative;
            z-index: 3;
        }
        .actions{
            display: flex;
            justify-content: end;
            align-items: center;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 25px;
            width: fit-content;
            padding-left: 40px;
            margin-left: 160px;

        }
        .actions >  *{
            margin-left: 10px;
        }
        .message-ChangePassword{
            position: fixed;
            top: 80px;
        }
</style>
<div class="container">
    @include('layouts.nav')
{{-- ✅ Box thông báo tự ẩn sau 10 giây --}}
<div class="message-ChangePassword">
    @if (session('success'))
        <div id="success-box" 
            style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;
                   padding: 12px 20px; border-radius: 8px; margin-bottom: 15px;
                   font-weight: 600; transition: opacity 0.5s ease;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div id="error-box"
            style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;
                   padding: 12px 20px; border-radius: 8px; margin-bottom: 15px;
                   font-weight: 600; transition: opacity 0.5s ease;">
            {{ session('error') }}
        </div>
    @endif

    {{-- ⚠️ Thêm id="error-box" cho validation errors --}}
    @if ($errors->any())
        <div id="error-box"
            style="background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba;
                   padding: 12px 20px; border-radius: 8px; margin-bottom: 15px;
                   font-weight: 600; transition: opacity 0.5s ease;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<div class="changePassword-field">
<form method="POST" action="{{ route('user.changePassword') }}">
    <h4 style="margin-bottom: 30px;font-weight: 600;">Thay đổi mật khẩu</h4>
    @csrf
    <div class="content-password">
        <label for="current_password">Mật khẩu hiện tại:</label>
        <input type="password" id="current_password" name="current_password" required>
    </div>
    <div class="content-password">
        <label for="new_password">Mật khẩu mới:</label>
        <input type="password" id="new_password" name="new_password" required>
    </div>
    <div class="content-password">
        <label for="new_password_confirmation">Xác nhận mật khẩu mới:</label>
        <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
    </div>
    <div class="actions">
        <a class="backBtn" href="{{ route('user.profile') }}">Quay lại</a>
    <button type="submit" class="confirmBtn btn-shadow">Đổi mật khẩu</button>
</form>


</div>
</div>

{{-- ✅ Script ẩn box sau 10 giây --}}
<script>
    setTimeout(() => {
        const box = document.getElementById('success-box') || document.getElementById('error-box');
        if (box) {
            box.style.opacity = '0';
            setTimeout(() => box.remove(), 500); // ẩn mượt rồi xóa khỏi DOM
        }
    }, 10000); // 10 giây
</script>
</div>

@endsection
