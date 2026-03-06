<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
}

body{
    background-image:url('/img/home-wallpaper.jpg');
    background-size:cover;
    background-position:center;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
    position:relative;
}

body::before{
    content:'';
    position:fixed;
    inset:0;
    background:linear-gradient(
        rgba(0,0,0,0.75),
        rgba(0,0,0,0.6)
    );
}

.container{
    width:100%;
    max-width:450px;

    background:rgba(255,255,255,0.08);
    backdrop-filter:blur(12px);

    border-radius:15px;

    border:1px solid rgba(255,255,255,0.2);

    box-shadow:0 10px 40px rgba(0,0,0,0.6);

    overflow:hidden;

    position:relative;
    z-index:2;

    animation:fadeIn .7s ease;
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

.header{
    background:rgba(0,0,0,0.35);
    text-align:center;
    padding:28px 20px;
    border-bottom:1px solid rgba(255,255,255,0.15);
}

.header h1{
    font-size:1.9rem;
    font-weight:700;
    color:white;
    letter-spacing:1px;
}

.form-container{
    padding:35px 32px;
}

.notification{
    padding:12px 15px;
    border-radius:8px;
    margin-bottom:20px;
    font-size:.9rem;
    display:flex;
    align-items:center;
    border-left:4px solid;
}

.notification.error{
    background:rgba(255,80,80,0.15);
    color:#ff6b6b;
    border-left-color:#ff6b6b;
}

.notification.success{
    background:rgba(80,255,150,0.15);
    color:#2ecc71;
    border-left-color:#2ecc71;
}

.notification i{
    margin-right:10px;
}

.form-group{
    margin-bottom:22px;
    position:relative;
}

.form-group label{
    display:block;
    margin-bottom:6px;
    font-size:.9rem;
    color:#ddd;
}

.form-group i{
    position:absolute;
    left:14px;
    top:41px;
    color:#aaa;
}

.input-field{
    width:100%;
    padding:13px 14px 13px 40px;

    border-radius:8px;
    border:1px solid rgba(255,255,255,0.25);

    background:rgba(255,255,255,0.1);
    color:white;

    font-size:.95rem;

    transition:.25s;
}

.input-field::placeholder{
    color:#ccc;
}

.input-field:focus{
    outline:none;
    border-color:#00c3ff;

    background:rgba(255,255,255,0.15);

    box-shadow:0 0 10px rgba(0,195,255,0.5);
}

.input-field.error{
    border-color:#ff6b6b;
    box-shadow:0 0 10px rgba(255,100,100,0.5);
}

.error-message{
    color:#ff6b6b;
    font-size:.8rem;
    margin-top:5px;
    display:flex;
    align-items:center;
}

.error-message i{
    margin-right:5px;
    position:static;
}

.field-requirements{
    font-size:.75rem;
    color:#aaa;
    margin-top:3px;
}

.password-rules{
    background:rgba(255,255,255,0.07);
    border-radius:8px;
    padding:14px;
    margin-top:15px;
    border-left:4px solid #00c3ff;
    font-size:.85rem;
    color:#ddd;
}

.password-rules ul{
    padding-left:18px;
    margin-top:6px;
}

.password-rules li{
    margin-bottom:4px;
}

.btn-submit{
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

    font-size:1rem;
    font-weight:600;

    cursor:pointer;

    margin-top:15px;

    transition:.25s;
}

.btn-submit:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 18px rgba(255,75,43,0.5);
}

.btn-submit i{
    margin-right:8px;
}

.login-link{
    text-align:center;
    margin-top:25px;
    font-size:.9rem;
    color:#bbb;
}

.login-link a{
    color:#00c3ff;
    text-decoration:none;
    font-weight:600;
}

.login-link a:hover{
    text-decoration:underline;
}

@media(max-width:480px){

    .container{
        max-width:100%;
    }

    .form-container{
        padding:28px 22px;
    }

    .header{
        padding:22px 15px;
    }

}
</style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Đăng ký tài khoản</h1>
        </div>
        
        <div class="form-container">
            <!-- Thông báo lỗi chung -->
            @if(session('error'))
            <div class="notification error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif
            
            <!-- Thông báo thành công -->
            @if(session('success'))
            <div class="notification success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif
            
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="username">Tên đăng nhập</label>
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" class="input-field @error('username') error @enderror" placeholder="Nhập tên đăng nhập" value="{{ old('username') }}" required>
                    @error('username')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="field-requirements">
                        Yêu cầu: 3-50 ký tự, chỉ chứa chữ cái, số và dấu gạch dưới
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" class="input-field @error('email') error @enderror" placeholder="Nhập email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="field-requirements">
                        Yêu cầu: Email hợp lệ và chưa được sử dụng
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" class="input-field @error('password') error @enderror" placeholder="Nhập mật khẩu" required>
                    @error('password')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="confirmPassword">Nhập lại mật khẩu</label>
                    <i class="fas fa-lock"></i>
                    <input type="password" id="confirmPassword" name="confirmPassword" class="input-field @error('confirmPassword') error @enderror" placeholder="Xác nhận mật khẩu" required>
                    @error('confirmPassword')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <i class="fas fa-phone"></i>
                    <input type="text" id="phone" name="phone" class="input-field @error('phone') error @enderror" placeholder="Nhập số điện thoại" value="{{ old('phone') }}" required>
                    @error('phone')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="field-requirements">
                        Yêu cầu: 10-11 chữ số
                    </div>
                </div>
                
                <div class="password-rules">
                    <strong>Yêu cầu mật khẩu:</strong>
                    <ul>
                        <li>Ít nhất 6 ký tự</li>
                        <li>Không vượt quá 100 ký tự</li>
                    </ul>
                </div>
                
                <button type="submit" class="btn-submit">
                    <i class="fas fa-user-plus"></i> Đăng ký
                </button>
            </form>
            
            <div class="login-link">
                Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập ngay</a>
            </div>
        </div>
    </div>

    <script>
        // Real-time validation feedback
        document.addEventListener('DOMContentLoaded', function() {
            const fields = ['username', 'email', 'password', 'confirmPassword', 'phone'];
            
            fields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (field) {
                    field.addEventListener('blur', function() {
                        validateField(this);
                    });
                    
                    // Validate ngay khi đang nhập (với confirmPassword)
                    if (fieldName === 'password' || fieldName === 'confirmPassword') {
                        field.addEventListener('input', function() {
                            if (fieldName === 'password') {
                                const confirmField = document.getElementById('confirmPassword');
                                if (confirmField.value) {
                                    validateField(confirmField);
                                }
                            }
                            validateField(this);
                        });
                    }
                }
            });

            function validateField(field) {
                const value = field.value.trim();
                const fieldName = field.name;
                let isValid = true;
                let errorMessage = '';

                switch(fieldName) {
                    case 'username':
                        if (value.length < 3) {
                            isValid = false;
                            errorMessage = 'Tên đăng nhập phải có ít nhất 3 ký tự';
                        } else if (value.length > 50) {
                            isValid = false;
                            errorMessage = 'Tên đăng nhập không được vượt quá 50 ký tự';
                        } else if (!/^[a-zA-Z0-9_]+$/.test(value)) {
                            isValid = false;
                            errorMessage = 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới';
                        } else if (['admin', 'administrator'].includes(value.toLowerCase())) {
                            isValid = false;
                            errorMessage = 'Tên đăng nhập này không được phép sử dụng';
                        }
                        break;
                    
                    case 'email':
                        if (!value) {
                            isValid = false;
                            errorMessage = 'Email không được để trống';
                        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                            isValid = false;
                            errorMessage = 'Email không hợp lệ';
                        }
                        break;
                    
                    case 'password':
                        if (value.length < 6) {
                            isValid = false;
                            errorMessage = 'Mật khẩu phải có ít nhất 6 ký tự';
                        } else if (value.length > 100) {
                            isValid = false;
                            errorMessage = 'Mật khẩu không được vượt quá 100 ký tự';
                        }
                        break;
                    
                    case 'confirmPassword':
                        const password = document.getElementById('password').value;
                        if (value !== password) {
                            isValid = false;
                            errorMessage = 'Mật khẩu xác nhận không khớp';
                        }
                        break;
                    
                    case 'phone':
                        if (!/^[0-9]{10,11}$/.test(value)) {
                            isValid = false;
                            errorMessage = 'Số điện thoại phải có 10-11 chữ số';
                        }
                        break;
                }

                updateFieldValidation(field, isValid, errorMessage);
            }

            function updateFieldValidation(field, isValid, errorMessage) {
                // Remove existing error message
                const existingError = field.parentNode.querySelector('.error-message.realtime');
                if (existingError) {
                    existingError.remove();
                }

                // Update field style
                field.classList.remove('error');
                
                if (!isValid && errorMessage) {
                    field.classList.add('error');
                    
                    // Add error message
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message realtime';
                    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i>${errorMessage}`;
                    field.parentNode.appendChild(errorDiv);
                }
            }
            
            // Validate form khi submit
            document.querySelector('form').addEventListener('submit', function(e) {
                let hasErrors = false;
                const fields = ['username', 'email', 'password', 'confirmPassword', 'phone'];
                
                fields.forEach(fieldName => {
                    const field = document.getElementById(fieldName);
                    validateField(field);
                    if (field.classList.contains('error')) {
                        hasErrors = true;
                    }
                });
                
                if (hasErrors) {
                    e.preventDefault();
                    // Focus vào field đầu tiên có lỗi
                    const firstError = document.querySelector('.input-field.error');
                    if (firstError) {
                        firstError.focus();
                    }
                }
            });
        });
    </script>
</body>
</html>