<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container-fluid { padding: 20px; }
        .form-section { background: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .table-section { background: white; padding: 20px; border-radius: 5px; }
        .btn-action { margin-right: 5px; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <h2 class="mb-4">Quản lý Tài khoản</h2>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Form Section -->
            <div class="col-md-4">
                <div class="form-section">
                    <h4 id="form-title">Thêm Tài khoản Mới</h4>
                    
                    <form id="account-form" method="POST" 
                          action="{{ isset($editMode) && $editMode ? route('admin.taikhoan.update', $editingAccount->TenDangNhap) : route('admin.taikhoan.store') }}">
                        @if(isset($editMode) && $editMode)
                            @method('PUT')
                        @endif
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Tên đăng nhập *</label>
                            <input type="text" class="form-control" name="TenDangNhap" id="TenDangNhap" 
                                   value="{{ old('TenDangNhap', $editingAccount->TenDangNhap ?? '') }}" 
                                   {{ isset($editMode) && $editMode ? 'readonly' : 'required' }}>
                            @error('TenDangNhap')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mật khẩu *</label>
                            <input type="password" class="form-control" name="MatKhau" id="MatKhau" 
                                   value="{{ old('MatKhau') }}" 
                                   {{ !isset($editMode) || !$editMode ? 'required' : '' }}>
                            <small class="text-muted">Để trống nếu không muốn thay đổi mật khẩu (khi sửa)</small>
                            @error('MatKhau')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Loại tài khoản *</label>
                            <select class="form-select" name="LoaiTaiKhoan" required>
                                <option value="User" {{ (old('LoaiTaiKhoan', $editingAccount->LoaiTaiKhoan ?? '') == 'User') ? 'selected' : '' }}>User</option>
                                <option value="Admin" {{ (old('LoaiTaiKhoan', $editingAccount->LoaiTaiKhoan ?? '') == 'Admin') ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('LoaiTaiKhoan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mã người dùng *</label>
                            <select class="form-select" name="MaNguoiDung" id="MaNguoiDung" 
                                    {{ isset($editMode) && $editMode ? 'disabled' : 'required' }}>
                                <option value="">Chọn mã người dùng</option>
                                @foreach($usersWithoutAccounts ?? [] as $user)
                                    <option value="{{ $user->MaNguoiDung }}" 
                                            {{ (old('MaNguoiDung', $editingAccount->MaNguoiDung ?? '') == $user->MaNguoiDung) ? 'selected' : '' }}>
                                        {{ $user->MaNguoiDung }} - {{ $user->HoTen }}
                                    </option>
                                @endforeach
                            </select>
                            @if(isset($editMode) && $editMode)
                                <input type="hidden" name="MaNguoiDung" value="{{ $editingAccount->MaNguoiDung }}">
                            @endif
                            @error('MaNguoiDung')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ isset($editMode) && $editMode ? 'Cập nhật' : 'Thêm mới' }}
                            </button>
                            @if(isset($editMode) && $editMode)
                                <a href="{{ route('admin.taikhoan.index') }}" class="btn btn-secondary">Hủy</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Section -->
            <div class="col-md-8">
                <div class="table-section">
                    <h4>Danh sách Tài khoản</h4>
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tên đăng nhập</th>
                                    <th>Loại tài khoản</th>
                                    <th>Mã người dùng</th>
                                    <th>Họ tên</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($taiKhoans as $tk)
                                <tr>
                                    <td>{{ $tk->TenDangNhap }}</td>
                                    <td>
                                        <span class="badge {{ $tk->LoaiTaiKhoan == 'Admin' ? 'bg-danger' : 'bg-primary' }}">
                                            {{ $tk->LoaiTaiKhoan }}
                                        </span>
                                    </td>
                                    <td>{{ $tk->MaNguoiDung }}</td>
                                    <td>{{ $tk->nguoiDung->HoTen ?? 'N/A' }}</td>
                                    <td>{{ $tk->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.taikhoan.edit', $tk->TenDangNhap) }}" 
                                           class="btn btn-sm btn-warning btn-action">Sửa</a>
                                        <form action="{{ route('admin.taikhoan.destroy', $tk->TenDangNhap) }}" 
                                              method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger btn-action" 
                                                    onclick="return confirm('Bạn có chắc muốn xóa tài khoản này?')">
                                                Xóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $taiKhoans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load users without accounts when page loads
        document.addEventListener('DOMContentLoaded', function() {
            fetch('{{ route("admin.taikhoan.users.without.accounts") }}')
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('MaNguoiDung');
                    select.innerHTML = '<option value="">Chọn mã người dùng</option>';
                    data.forEach(user => {
                        const option = document.createElement('option');
                        option.value = user.MaNguoiDung;
                        option.textContent = `${user.MaNguoiDung} - ${user.HoTen}`;
                        select.appendChild(option);
                    });
                });
        });
    </script>
</body>
</html>