<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Rạp chiếu phim</title>
   <style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
    background-image:url('/img/home-wallpaper.jpg');
    background-size:cover;
    background-position:center;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    position:relative;
}
body::before{
    content:'';
    position:absolute;
    inset:0;
    background:linear-gradient(
        rgba(0,0,0,0.75),
        rgba(0,0,0,0.6)
    );
}
.login-container{
    width:380px;
    padding:45px 40px;

    background:rgba(255,255,255,0.08);
    backdrop-filter:blur(12px);

    border-radius:15px;

    border:1px solid rgba(255,255,255,0.2);

    box-shadow:
        0 10px 40px rgba(0,0,0,0.6);

    position:relative;
    z-index:2;

    animation:fadeIn 0.7s ease;
}


@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(20px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

/
.login-container h2{
    text-align:center;
    margin-bottom:35px;
    font-size:28px;
    font-weight:700;
    color:white;
    letter-spacing:1px;
}


.form-group{
    margin-bottom:22px;
}


.form-group label{
    display:block;
    margin-bottom:6px;
    font-size:14px;
    color:#ddd;
}


.form-group input{
    width:100%;
    padding:13px 14px;

    border-radius:8px;
    border:1px solid rgba(255,255,255,0.25);

    background:rgba(255,255,255,0.1);
    color:white;

    font-size:14px;

    transition:all 0.25s ease;
}


.form-group input::placeholder{
    color:#ccc;
}


.form-group input:focus{
    outline:none;
    border-color:#00c3ff;

    background:rgba(255,255,255,0.15);

    box-shadow:0 0 10px rgba(0,195,255,0.5);
}


.login-btn{
    width:100%;
    padding:14px;

    border:none;
    border-radius:8px;

    background:linear-gradient(
        135deg,
        #ff416c,
        #ff4b2b
    );

    color:white;

    font-size:15px;
    font-weight:600;

    cursor:pointer;

    transition:all .25s ease;
}


.login-btn:hover{
    transform:translateY(-2px);

    box-shadow:
        0 8px 18px rgba(255,75,43,0.5);
}


.register-link{
    text-align:center;
    margin-top:25px;
}

.register-link a{
    color:#00c3ff;
    font-size:14px;
    text-decoration:none;
    transition:.2s;
}

.register-link a:hover{
    text-decoration:underline;
}


.error{
    color:#ff6b6b;
    font-size:13px;
    margin-top:5px;
}


@media(max-width:480px){
    .login-container{
        width:90%;
        padding:35px 25px;
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