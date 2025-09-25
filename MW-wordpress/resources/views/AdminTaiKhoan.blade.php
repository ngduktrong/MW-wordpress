{{-- resources/views/AdminTaiKhoan.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản lý Tài khoản</title>
    <style>
        /* giữ layout đơn giản: 2 cột: danh sách | form */
        body { font-family: Arial, sans-serif; padding: 16px; }
        .container { display: flex; gap: 20px; }
        .card { border: 1px solid #ddd; padding: 12px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .left { flex: 2; }
        .right { flex: 1; min-width: 320px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #eee; padding: 8px; text-align: left; }
        th { background: #f7f7f7; }
        .actions button { margin-right: 6px; }
        .form-row { margin-bottom: 8px; }
        label { display:block; font-weight:600; margin-bottom:4px; }
    </style>
</head>
<body>

<h2>Quản lý Tài khoản</h2>

@if(session('success'))
    <div style="padding:8px;background:#e6ffed;border:1px solid #b7f2c8;margin-bottom:12px;">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="padding:8px;background:#ffe6e6;border:1px solid #f2b7b7;margin-bottom:12px;">
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

    function resetForm() {
        editingUsername = null;
        document.getElementById('form-title').innerText = 'Thêm tài khoản';
        document.getElementById('TenDangNhap').value = '';
        document.getElementById('TenDangNhap').removeAttribute('readonly');
        document.getElementById('MatKhau').value = '';
        document.getElementById('LoaiTaiKhoan').value = 'user';
        
        // THÊM: Enable lại trường mã người dùng khi reset form
        document.getElementById('MaNguoiDung').value = '';
        document.getElementById('MaNguoiDung').removeAttribute('disabled');
        document.getElementById('MaNguoiDung').required = true;
        
        document.getElementById('submit-btn').innerText = 'Thêm';
        document.getElementById('cancel-btn').style.display = 'none';
    }

    function onEdit(tenDangNhap) {
        // fetch data từ server
        fetch(`/admin/taikhoan/${encodeURIComponent(tenDangNhap)}/edit`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        }).then(r => r.json()).then(json => {
            if (!json.success) {
                alert('Lỗi khi tải dữ liệu tài khoản');
                return;
            }
            const data = json.data;
            editingUsername = data.TenDangNhap;
            document.getElementById('form-title').innerText = 'Sửa tài khoản: ' + editingUsername;
            document.getElementById('TenDangNhap').value = data.TenDangNhap;
            document.getElementById('TenDangNhap').setAttribute('readonly', 'readonly');
            // mật khẩu không hiển thị
            document.getElementById('MatKhau').value = '';
            document.getElementById('LoaiTaiKhoan').value = data.LoaiTaiKhoan || 'user';
            
            // THÊM: Disable trường mã người dùng khi sửa
            document.getElementById('MaNguoiDung').value = data.MaNguoiDung || '';
            document.getElementById('MaNguoiDung').setAttribute('disabled', 'disabled');
            document.getElementById('MaNguoiDung').required = false;
            
            document.getElementById('submit-btn').innerText = 'Lưu';
            document.getElementById('cancel-btn').style.display = 'inline-block';
        }).catch(err => {
            console.error(err);
            alert('Lỗi mạng khi lấy dữ liệu');
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
                location.reload();
            } else {
                alert('Xoá thất bại: ' + (json.message || 'Unknown'));
            }
        }).catch(err => {
            console.error(err);
            alert('Lỗi khi xoá, kiểm tra console.');
        });
    }

    async function onSubmitForm(e) {
        e.preventDefault();

        const tenDangNhap = document.getElementById('TenDangNhap').value.trim();
        const matKhau = document.getElementById('MatKhau').value;
        const loai = document.getElementById('LoaiTaiKhoan').value;
        const maNguoiDung = document.getElementById('MaNguoiDung').value || null;

        if (!tenDangNhap) {
            alert('Vui lòng nhập TenDangNhap');
            return false;
        }

        // THÊM: Kiểm tra bắt buộc mã người dùng khi thêm mới
        if (!editingUsername && !maNguoiDung) {
            alert('Vui lòng chọn mã người dùng khi thêm tài khoản mới');
            return false;
        }

        // chuẩn bị payload
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
                // THÊM: Khi sửa, không gửi MaNguoiDung vì không cho phép sửa
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
                    location.reload();
                } else {
                    alert('Lỗi server: ' + (j.message || 'unknown'));
                }
            } else {
                const err = await res.json();
                // show validation errors if có
                if (err.errors) {
                    const msgs = [];
                    for (const k in err.errors) {
                        msgs.push(err.errors[k].join(', '));
                    }
                    alert('Lỗi xác thực:\n' + msgs.join('\n'));
                } else {
                    alert('Lỗi khi gửi form');
                }
            }
        } catch (error) {
            console.error(error);
            alert('Lỗi mạng hoặc server');
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
                // giữ option đầu
                sel.innerHTML = '<option value="">-- Chọn người dùng --</option>';
                j.data.forEach(u => {
                    const opt = document.createElement('option');
                    opt.value = u.MaNguoiDung;
                    // nếu có tên hiển thị (HoTen) thì show, nếu không thì chỉ show id
                    opt.textContent = u.MaNguoiDung + (u.HoTen ? ' - ' + u.HoTen : '');
                    sel.appendChild(opt);
                });
            } else {
                alert('Không tải được danh sách người dùng');
            }
        } catch (err) {
            console.error(err);
            alert('Lỗi khi tải danh sách người dùng chưa có tài khoản');
        }
    }

    // init
    resetForm();
</script>

</body>
</html>