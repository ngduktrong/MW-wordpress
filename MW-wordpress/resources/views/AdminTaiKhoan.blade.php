{{-- resources/views/AdminTaiKhoan.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản lý Tài khoản</title>
    <style>
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        background-color: #f8f9fa;
        color: #333;
        line-height: 1.6;
        padding: 20px;
    }
    
    h2 {
        color: #1a1a1a;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
        text-align: center;
    }
    
    /* Nút quay lại Dashboard */
    .btn-back-dashboard {
        background-color: #2c3e50;
        border: 1px solid #2c3e50;
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 6px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .btn-back-dashboard:hover {
        background-color: #1a252f;
        border-color: #1a252f;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        text-decoration: none;
    }
    
    .container {
        display: flex;
        gap: 25px;
        margin-top: 1rem;
    }
    
    .card {
        background: white;
        border: 1px solid #e0e0e0;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .left {
        flex: 2;
    }
    
    .right {
        flex: 1;
        min-width: 350px;
    }
    
    h3 {
        color: #1a1a1a;
        font-weight: 600;
        margin-bottom: 1.2rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e9ecef;
        text-align: center;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 0 0 1px #e9ecef;
    }
    
    th, td {
        border: 1px solid #e9ecef;
        padding: 0.75rem;
        text-align: left;
    }
    
    th {
        background: #2c3e50;
        color: white;
        font-weight: 600;
        border-color: #2c3e50;
    }
    
    tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }
    
    tbody tr:hover {
        background-color: #e9ecef;
    }
    
    .actions button {
        margin-right: 6px;
        padding: 0.4rem 0.8rem;
        border: none;
        border-radius: 4px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .actions button:first-child {
        background-color: #e9b949;
        color: #000;
    }
    
    .actions button:first-child:hover {
        background-color: #d4a63c;
        transform: translateY(-1px);
    }
    
    .actions button:last-child {
        background-color: #dc3545;
        color: white;
    }
    
    .actions button:last-child:hover {
        background-color: #c82333;
        transform: translateY(-1px);
    }
    
    .form-row {
        margin-bottom: 1rem;
    }
    
    label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #495057;
    }
    
    input, select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }
    
    input:focus, select:focus {
        border-color: #6c757d;
        box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.15);
        outline: none;
    }
    
    input[readonly] {
        background-color: #f8f9fa;
        color: #6c757d;
    }
    
    select[disabled] {
        background-color: #f8f9fa;
        color: #6c757d;
    }
    
    small {
        color: #6c757d;
        font-weight: normal;
    }
    
    button[type="submit"], 
    button[type="button"]:not(.actions button) {
        background-color: #2c3e50;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    button[type="submit"]:hover, 
    button[type="button"]:not(.actions button):hover {
        background-color: #1a252f;
        transform: translateY(-1px);
    }
    
    #cancel-btn {
        background-color: #6c757d;
        margin-left: 0.5rem;
    }
    
    #cancel-btn:hover {
        background-color: #5a6268;
    }
    
    .alert-success {
        padding: 12px 16px;
        background: #d4edda;
        border: 1px solid #c3e6cb;
        border-radius: 6px;
        color: #155724;
        margin-bottom: 1rem;
        text-align: center;
    }
    
    .alert-error {
        padding: 12px 16px;
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 6px;
        color: #721c24;
        margin-bottom: 1rem;
        text-align: center;
    }
    
    span[style*="color:red"] {
        color: #dc3545 !important;
    }
    
    @media (max-width: 768px) {
        .container {
            flex-direction: column;
        }
        
        .right {
            min-width: auto;
        }
        
        body {
            padding: 15px;
        }
    }
    .alert-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 6px;
        color: white;
        font-weight: 500;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideIn 0.3s ease-out;
    }

    .alert-toast.success {
        background-color: #28a745;
        border-left: 4px solid #1e7e34;
    }

    .alert-toast.error {
        background-color: #dc3545;
        border-left: 4px solid #c82333;
    }

    .alert-toast.warning {
        background-color: #ffc107;
        color: #212529;
        border-left: 4px solid #e0a800;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .fade-out {
        animation: fadeOut 0.5s ease-in forwards;
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }
</style>
</head>
<body>

        <h2>Quản lý Tài khoản</h2>
    <a href="{{ route('admin.dashboard') }}" class="btn-back-dashboard">
        <i class="fas fa-arrow-left"></i> Quay lại Dashboard
    </a>

    {{-- THÔNG BÁO TỪ SERVER --}}
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert-error">
            {{ session('error') }}
        </div>
    @endif

<div class="container">
    <div class="card left">
        <h3>Danh sách tài khoản</h3>
        <table id="accounts-table">
            <thead>
                <tr>
                    <th>TenDangNhap</th>
                    <th>Loai</th>
                    <th>MaNguoiDung</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($taiKhoans as $tk)
                    <tr data-username="{{ $tk->TenDangNhap }}">
                        <td>{{ $tk->TenDangNhap }}</td>
                        <td>{{ $tk->LoaiTaiKhoan }}</td>
                        <td>{{ $tk->MaNguoiDung }}</td>
                        <td class="actions">
                            <button type="button" onclick="onEdit('{{ $tk->TenDangNhap }}')">Sửa</button>
                            <button type="button" onclick="onDelete('{{ $tk->TenDangNhap }}')">Xoá</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card right">
        <h3 id="form-title">Thêm tài khoản</h3>

        <form id="account-form" onsubmit="return onSubmitForm(event);">
            <div class="form-row">
                <label for="TenDangNhap">Tên đăng nhập</label>
                <input type="text" id="TenDangNhap" name="TenDangNhap" maxlength="50" required>
            </div>

            <div class="form-row">
                <label for="MatKhau">Mật khẩu <small>(để trống khi sửa nếu không đổi)</small></label>
                <input type="password" id="MatKhau" name="MatKhau">
            </div>

            <div class="form-row">
                <label for="LoaiTaiKhoan">Loại tài khoản</label>
                <select id="LoaiTaiKhoan" name="LoaiTaiKhoan" required>
                    <option value="user">user</option>
                    <option value="admin">admin</option>
                </select>
            </div>

            <div class="form-row">
                <label for="MaNguoiDung">Người dùng <span style="color:red;">*</span> (bắt buộc khi thêm mới, không thể thay đổi khi sửa)</label>
                <select id="MaNguoiDung" name="MaNguoiDung" required>
                    <option value="">-- Chọn người dùng --</option>
                    {{-- nếu backend đã gửi danh sách nguoiDungs, hiển thị luôn --}}
                    @foreach ($nguoiDungs as $nd)
                        @php
                            // nếu đã có tài khoản với MaNguoiDung thì skip
                        @endphp
                        <option value="{{ $nd->MaNguoiDung }}">{{ $nd->MaNguoiDung }} - {{ $nd->HoTen ?? '' }}</option>
                    @endforeach
                </select>
                <div style="margin-top:6px;">
                    <button type="button" onclick="fetchUsersWithoutAccounts()">Tải danh sách người dùng chưa có tài khoản</button>
                </div>
            </div>

            <div style="margin-top:12px;">
                <button type="submit" id="submit-btn">Thêm</button>
                <button type="button" id="cancel-btn" onclick="resetForm()" style="display:none;margin-left:8px;">Huỷ</button>
            </div>
        </form>
    </div>
</div>

<script>
    // URLs từ route helpers
    const indexUrl = "{{ route('admin.taikhoan.index') }}";
    const storeUrl = "{{ route('admin.taikhoan.store') }}";
    const usersWithoutAccountsUrl = "{{ route('admin.taikhoan.users.without.accounts') }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // đang edit?
    let editingUsername = null;

    // HÀM HIỂN THỊ THÔNG BÁO ĐỘNG
    function showAlert(message, type = 'success') {
        // Tạo element thông báo
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert-toast ${type}`;
        alertDiv.innerHTML = `
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" 
                        style="background: none; border: none; color: inherit; cursor: pointer; margin-left: 10px; font-size: 16px;">
                    ×
                </button>
            </div>
        `;
        
        // Thêm vào body
        document.body.appendChild(alertDiv);
        
        // Tự động xóa sau 5 giây
        setTimeout(() => {
            if (alertDiv.parentElement) {
                alertDiv.classList.add('fade-out');
                setTimeout(() => {
                    if (alertDiv.parentElement) {
                        alertDiv.remove();
                    }
                }, 500);
            }
        }, 5000);
    }

    function resetForm() {
        editingUsername = null;
        document.getElementById('form-title').innerText = 'Thêm tài khoản';
        document.getElementById('TenDangNhap').value = '';
        document.getElementById('TenDangNhap').removeAttribute('readonly');
        document.getElementById('MatKhau').value = '';
        document.getElementById('LoaiTaiKhoan').value = 'user';
        
        document.getElementById('MaNguoiDung').value = '';
        document.getElementById('MaNguoiDung').removeAttribute('disabled');
        document.getElementById('MaNguoiDung').required = true;
        
        document.getElementById('submit-btn').innerText = 'Thêm';
        document.getElementById('cancel-btn').style.display = 'none';
    }

    function onEdit(tenDangNhap) {
        fetch(`/admin/taikhoan/${encodeURIComponent(tenDangNhap)}/edit`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        }).then(r => r.json()).then(json => {
            if (!json.success) {
                showAlert('Lỗi khi tải dữ liệu tài khoản', 'error');
                return;
            }
            const data = json.data;
            editingUsername = data.TenDangNhap;
            document.getElementById('form-title').innerText = 'Sửa tài khoản: ' + editingUsername;
            document.getElementById('TenDangNhap').value = data.TenDangNhap;
            document.getElementById('TenDangNhap').setAttribute('readonly', 'readonly');
            document.getElementById('MatKhau').value = '';
            document.getElementById('LoaiTaiKhoan').value = data.LoaiTaiKhoan || 'user';
            
            document.getElementById('MaNguoiDung').value = data.MaNguoiDung || '';
            document.getElementById('MaNguoiDung').setAttribute('disabled', 'disabled');
            document.getElementById('MaNguoiDung').required = false;
            
            document.getElementById('submit-btn').innerText = 'Lưu';
            document.getElementById('cancel-btn').style.display = 'inline-block';
            
            showAlert('Đã tải thông tin tài khoản thành công', 'success');
        }).catch(err => {
            console.error(err);
            showAlert('Lỗi mạng khi lấy dữ liệu', 'error');
        });
    }

    function onDelete(tenDangNhap) {
        if (!confirm('Bạn có chắc muốn xoá tài khoản "' + tenDangNhap + '" ?')) return;

        fetch(`/admin/taikhoan/${encodeURIComponent(tenDangNhap)}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            }
        }).then(r => {
            if (r.ok) return r.json();
            return r.json().then(j => { throw j; });
        }).then(json => {
            if (json.success) {
                showAlert(json.message || 'Xóa tài khoản thành công!', 'success');
                // Reload sau 1 giây để hiển thị dữ liệu mới
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showAlert('Xoá thất bại: ' + (json.message || 'Unknown'), 'error');
            }
        }).catch(err => {
            console.error(err);
            showAlert('Lỗi khi xoá, kiểm tra console.', 'error');
        });
    }

    async function onSubmitForm(e) {
        e.preventDefault();

        const tenDangNhap = document.getElementById('TenDangNhap').value.trim();
        const matKhau = document.getElementById('MatKhau').value;
        const loai = document.getElementById('LoaiTaiKhoan').value;
        const maNguoiDung = document.getElementById('MaNguoiDung').value || null;

        if (!tenDangNhap) {
            showAlert('Vui lòng nhập tên đăng nhập', 'error');
            return false;
        }

        if (!editingUsername && !maNguoiDung) {
            showAlert('Vui lòng chọn mã người dùng khi thêm tài khoản mới', 'error');
            return false;
        }

        const payload = {
            TenDangNhap: tenDangNhap,
            MatKhau: matKhau,
            LoaiTaiKhoan: loai,
            MaNguoiDung: maNguoiDung
        };

        try {
            let url = storeUrl;
            let method = 'POST';
            if (editingUsername) {
                url = `/admin/taikhoan/${encodeURIComponent(editingUsername)}`;
                method = 'PUT';
                delete payload.MaNguoiDung;
            }

            const res = await fetch(url, {
                method: method,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            });

            if (res.ok) {
                const j = await res.json();
                if (j.success) {
                    showAlert(j.message || (editingUsername ? 'Cập nhật tài khoản thành công!' : 'Tạo tài khoản thành công!'), 'success');
                    // Reload sau 1 giây để hiển thị dữ liệu mới
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showAlert('Lỗi server: ' + (j.message || 'unknown'), 'error');
                }
            } else {
                const err = await res.json();
                if (err.errors) {
                    const msgs = [];
                    for (const k in err.errors) {
                        msgs.push(err.errors[k].join(', '));
                    }
                    showAlert('Lỗi xác thực:\n' + msgs.join('\n'), 'error');
                } else {
                    showAlert('Lỗi khi gửi form: ' + (err.message || 'Unknown error'), 'error');
                }
            }
        } catch (error) {
            console.error(error);
            showAlert('Lỗi mạng hoặc server', 'error');
        }

        return false;
    }

    async function fetchUsersWithoutAccounts() {
        try {
            const res = await fetch(usersWithoutAccountsUrl, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const j = await res.json();
            if (j.success) {
                const sel = document.getElementById('MaNguoiDung');
                sel.innerHTML = '<option value="">-- Chọn người dùng --</option>';
                j.data.forEach(u => {
                    const opt = document.createElement('option');
                    opt.value = u.MaNguoiDung;
                    opt.textContent = u.MaNguoiDung + (u.HoTen ? ' - ' + u.HoTen : '');
                    sel.appendChild(opt);
                });
                showAlert('Đã tải danh sách người dùng chưa có tài khoản thành công', 'success');
            } else {
                showAlert('Không tải được danh sách người dùng', 'error');
            }
        } catch (err) {
            console.error(err);
            showAlert('Lỗi khi tải danh sách người dùng chưa có tài khoản', 'error');
        }
    }

    // init
    resetForm();
</script>

</body>
</html>