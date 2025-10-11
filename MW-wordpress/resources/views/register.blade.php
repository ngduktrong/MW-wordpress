<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(120deg, #1e5799, #207cca, #2989d8, #1e5799);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            width: 100%;
            max-width: 450px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .header {
            background: #2980b9;
            color: white;
            text-align: center;
            padding: 25px 20px;
            position: relative;
        }
        
        .header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: #f1c40f;
            border-radius: 2px;
        }
        
        .form-container {
            padding: 30px;
        }
        
        .notification {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
        }
        
        .notification.error {
            background-color: #ffebee;
            color: #c62828;
            border-left: 4px solid #c62828;
        }
        
        .notification.success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #2e7d32;
        }
        
        .notification i {
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #2c3e50;
            font-size: 0.95rem;
        }
        
        .form-group i {
            position: absolute;
            left: 15px;
            top: 40px;
            color: #7f8c8d;
            font-size: 1rem;
        }
        
        .input-field {
            width: 100%;
            padding: 14px 14px 14px 40px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f9f9f9;
        }
        
        .input-field:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
            background: white;
        }

        .input-field.error {
            border-color: #c62828;
            box-shadow: 0 0 0 2px rgba(198, 40, 40, 0.1);
        }
        
        .error-message {
            color: #c62828;
            font-size: 0.85rem;
            margin-top: 5px;
            display: flex;
            align-items: center;
        }
        
        .error-message i {
            margin-right: 5px;
            font-size: 0.8rem;
        }
        
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: #2980b9;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            letter-spacing: 0.5px;
        }
        
        .btn-submit:hover {
            background: #2471a3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-submit:active {
            transform: translateY(0);
        }
        
        .login-link {
            text-align: center;
            margin-top: 25px;
            color: #7f8c8d;
            font-size: 0.95rem;
        }
        
        .login-link a {
            color: #2980b9;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }
        
        .login-link a:hover {
            color: #2471a3;
            text-decoration: underline;
        }
        
        .password-rules {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            border-left: 4px solid #3498db;
            font-size: 0.85rem;
            color: #555;
        }
        
        .password-rules ul {
            padding-left: 20px;
            margin-top: 8px;
        }
        
        .password-rules li {
            margin-bottom: 5px;
        }

        .field-requirements {
            font-size: 0.8rem;
            color: #666;
            margin-top: 3px;
        }
        
        @media (max-width: 480px) {
            .container {
                max-width: 100%;
            }
            
            .form-container {
                padding: 25px 20px;
            }
            
            .header {
                padding: 20px 15px;
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