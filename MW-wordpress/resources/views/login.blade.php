<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Rạp chiếu phim</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 350px;
        }
        .login-container h2 {
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
        .role-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        .role-btn {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            background: #f9f9f9;
            cursor: pointer;
            text-align: center;
            border-radius: 4px;
        }
        .role-btn.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }
        .login-btn {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .login-btn:hover {
            background: #0056b3;
        }
        .register-link {
            text-align: center;
            margin-top: 15px;
        }
        .register-link a {
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
            
            <div class="role-buttons">
                <div class="role-btn active" data-role="admin">Admin</div>
                <div class="role-btn" data-role="user">Khách Hàng</div>
            </div>
            <input type="hidden" name="LoaiTaiKhoan" id="LoaiTaiKhoan" value="admin">
            
            <button type="submit" class="login-btn">Đăng nhập</button>
            
            <div class="register-link">
                <a href="{{ route('register') }}">Đăng ký tài khoản</a>
            </div>
        </form>
    </div>

    <script>
        document.querySelectorAll('.role-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('LoaiTaiKhoan').value = this.getAttribute('data-role');
            });
        });
    </script>
</body>
</html>