<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Rạp chiếu phim</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .register-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 400px;
        }
        .register-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .register-btn {
            width: 100%;
            padding: 10px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .register-btn:hover {
            background: #218838;
        }
        .login-link {
            text-align: center;
            margin-top: 15px;
        }
        .login-link a {
            color: #007bff;
            text-decoration: none;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Đăng ký tài khoản</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="HoTen">Họ tên</label>
                <input type="text" id="HoTen" name="HoTen" value="{{ old('HoTen') }}" required>
                @error('HoTen')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="SoDienThoai">Số điện thoại</label>
                <input type="text" id="SoDienThoai" name="SoDienThoai" value="{{ old('SoDienThoai') }}" required>
                @error('SoDienThoai')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="Email">Email</label>
                <input type="email" id="Email" name="Email" value="{{ old('Email') }}" required>
                @error('Email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
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
            
            <div class="form-group">
                <label for="MatKhau_confirmation">Xác nhận mật khẩu</label>
                <input type="password" id="MatKhau_confirmation" name="MatKhau_confirmation" required>
            </div>
            
            <button type="submit" class="register-btn">Đăng ký</button>
            
            <div class="login-link">
                <a href="{{ route('login') }}">Nếu bạn đã có tài khoản hãy đăng nhập</a>
            </div>
        </form>
    </div>
</body>
</html>