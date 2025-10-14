<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Rạp chiếu phim</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-image: url('/img/home-wallpaper.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        position: relative;
    }

    body::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        z-index: 1;
    }

    .login-container {
        background: rgba(255, 255, 255, 0.95);
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        width: 380px;
        position: relative;
        z-index: 2;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transform: translateY(0);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .login-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
    }

    .login-container h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #2c3e50;
        font-size: 28px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #34495e;
        font-size: 14px;
    }

    .form-group input {
        width: 100%;
        padding: 12px;
        border: 2px solid #e1e8ed;
        border-radius: 6px;
        box-sizing: border-box;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .form-group input:focus {
        outline: none;
        border-color: #3498db;
        background: white;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }

    .login-btn {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .login-btn:hover {
        background: linear-gradient(135deg, #2980b9, #3498db);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4);
    }

    .login-btn:active {
        transform: translateY(0);
    }

    .register-link {
        text-align: center;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e1e8ed;
    }

    .register-link a {
        color: #3498db;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .register-link a:hover {
        color: #2980b9;
        text-decoration: underline;
    }

    .error {
        color: #e74c3c;
        font-size: 13px;
        margin-top: 6px;
        font-weight: 500;
        padding-left: 5px;
    }

    @media (max-width: 480px) {
        .login-container {
            width: 90%;
            padding: 30px 25px;
            margin: 20px;
        }
    }
</style>
</head>
<body>
    <div class="login-container">
        <h2>Đăng nhập</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="TenDangNhap">Tên đăng nhập</label>
                <input type="text" id="TenDangNhap" name="TenDangNhap" value="{{ old('TenDangNhap') }}" required>
                @error('TenDangNhap')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="MatKhau">Mật khẩu</label>
                <input type="password" id="MatKhau" name="MatKhau" required>
                @error('MatKhau')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="login-btn">Đăng nhập</button>
            
            <div class="register-link">
                <a href="{{ route('register') }}">Đăng ký tài khoản</a>
            </div>
        </form>
    </div>
</body>
</html>