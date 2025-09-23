<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">QUẢN LÝ TÀI KHOẢN</h2>
        
        <!-- Form thêm/sửa tài khoản -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 id="formTitle">Thêm Tài Khoản Mới</h5>
            </div>
            <div class="card-body">
                <form id="taiKhoanForm" method="POST">
                    @csrf
                    <input type="hidden" id="editId" name="editId">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tên đăng nhập *</label>
                                <input type="text" class="form-control" id="TenDangNhap" name="TenDangNhap" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Mật khẩu *</label>
                                <input type="password" class="form-control" id="MatKhau" name="MatKhau" required>
                                <small class="text-muted">Để trống nếu không muốn đổi mật khẩu khi sửa</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Loại tài khoản</label>
                                <input type="text" class="form-control" id="LoaiTaiKhoanDisplay" readonly>
                                <small class="text-muted">Tự động xác định theo mã người dùng</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Mã người dùng *</label>
                                <input type="text" class="form-control" id="MaNguoiDung" name="MaNguoiDung" required>
                                <div id="maNguoiDungFeedback" class="mt-1"></div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-success" id="submitBtn">Thêm mới</button>
                    <button type="button" class="btn btn-secondary" onclick="resetForm()" id="cancelBtn" style="display:none;">Hủy</button>
                </form>
            </div>
        </div>

        <!-- Danh sách tài khoản -->
        <div class="card">
            <div class="card-header">
                <h5>Danh sách Tài khoản</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tên đăng nhập</th>
                                <th>Mật khẩu (hash)</th>
                                <th>Loại tài khoản</th>
                                <th>Mã người dùng</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($taiKhoans as $tk)
                            <tr>
                                <td>{{ $tk->TenDangNhap }}</td>
                                <td title="{{ $tk->MatKhau }}">{{ substr($tk->MatKhau, 0, 20) }}...</td>
                                <td>
                                    <span class="badge {{ $tk->LoaiTaiKhoan == 'Admin' ? 'bg-danger' : 'bg-primary' }}">
                                        {{ $tk->LoaiTaiKhoan }}
                                    </span>
                                </td>
                                <td>{{ $tk->MaNguoiDung ?? 'N/A' }}</td>
                                <td>{{ $tk->nguoiDung->HoTen ?? 'N/A' }}</td>
                                <td>{{ $tk->nguoiDung->Email ?? 'N/A' }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editTaiKhoan('{{ $tk->TenDangNhap }}')">
                                        Sửa
                                    </button>
                                    <!-- Sửa phần xóa: dùng URL trực tiếp -->
                                    <form action="/admin/taikhoan/{{ $tk->TenDangNhap }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">
                                            Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function resetForm() {
            document.getElementById('taiKhoanForm').reset();
            document.getElementById('editId').value = '';
            document.getElementById('formTitle').textContent = 'Thêm Tài Khoản Mới';
            document.getElementById('submitBtn').textContent = 'Thêm mới';
            document.getElementById('cancelBtn').style.display = 'none';
            document.getElementById('LoaiTaiKhoanDisplay').value = '';
            document.getElementById('MatKhau').required = true;
        }

        function checkMaNguoiDung() {
            const maNguoiDung = document.getElementById('MaNguoiDung').value;
            const feedback = document.getElementById('maNguoiDungFeedback');
            
            if (!maNguoiDung) {
                feedback.innerHTML = '';
                return;
            }

            fetch(`/admin/taikhoan/check-manguoidung/${maNguoiDung}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        if (data.hasAccount) {
                            feedback.innerHTML = '<span class="text-danger">Mã người dùng đã có tài khoản</span>';
                            document.getElementById('LoaiTaiKhoanDisplay').value = '';
                        } else {
                            feedback.innerHTML = '<span class="text-success">Mã người dùng hợp lệ</span>';
                            const loaiTaiKhoan = data.nguoiDung.LoaiNguoiDung === 'NhanVien' ? 'Admin' : 'User';
                            document.getElementById('LoaiTaiKhoanDisplay').value = loaiTaiKhoan;
                        }
                    } else {
                        feedback.innerHTML = '<span class="text-danger">Mã người dùng không tồn tại</span>';
                        document.getElementById('LoaiTaiKhoanDisplay').value = '';
                    }
                });
        }

        function editTaiKhoan(id) {
            fetch(`/admin/taikhoan/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editId').value = data.TenDangNhap;
                    document.getElementById('TenDangNhap').value = data.TenDangNhap;
                    document.getElementById('MaNguoiDung').value = data.MaNguoiDung;
                    document.getElementById('LoaiTaiKhoanDisplay').value = data.LoaiTaiKhoan;
                    
                    document.getElementById('formTitle').textContent = 'Sửa Tài Khoản';
                    document.getElementById('submitBtn').textContent = 'Cập nhật';
                    document.getElementById('cancelBtn').style.display = 'inline-block';
                    document.getElementById('MatKhau').required = false;
                    
                    // Kiểm tra mã người dùng
                    checkMaNguoiDung();
                });
        }

        // Xử lý submit form
        document.getElementById('taiKhoanForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const editId = document.getElementById('editId').value;
            
            let url = '/admin/taikhoan';
            let method = 'POST';
            
            if (editId) {
                url = `/admin/taikhoan/${editId}`;
                method = 'PUT';
                formData.append('_method', 'PUT');
            }
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    return response.json().then(data => {
                        if (data.success) {
                            window.location.reload();
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra: ' + error.message);
            });
        });

        // Kiểm tra mã người dùng khi rời khỏi trường
        document.getElementById('MaNguoiDung').addEventListener('blur', checkMaNguoiDung);
    </script>
</body>
</html>