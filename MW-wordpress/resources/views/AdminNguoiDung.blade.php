{{-- resources/views/AdminNguoiDung.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Người Dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        background-color: #f8f9fa;
        color: #333;
        line-height: 1.6;
    }
    
    .container {
        max-width: 1200px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        padding: 2rem;
        margin-top: 2rem;
        margin-bottom: 2rem;
    }
    
    h1 {
        color: #1a1a1a;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e9ecef;
    }
    
    .form-section {
        background: #f8f9fa;
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 2.5rem;
        border: 1px solid #e9ecef;
    }
    
    .form-section h4 {
        color: #1a1a1a;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #dee2e6;
    }
    
    .table-section {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    
    .table-section h4 {
        color: #1a1a1a;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #dee2e6;
    }
    
    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border: 1px solid #ced4da;
        border-radius: 6px;
        padding: 0.75rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #6c757d;
        box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.15);
    }
    
    .btn {
        border-radius: 6px;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-primary {
        background-color: #2c3e50;
        border-color: #2c3e50;
    }
    
    .btn-primary:hover {
        background-color: #1a252f;
        border-color: #1a252f;
        transform: translateY(-1px);
    }
    
    .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
    }
    
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
    }
    
    .btn-warning {
        background-color: #e9b949;
        border-color: #e9b949;
        color: #000;
    }
    
    .btn-warning:hover {
        background-color: #d4a63c;
        border-color: #d4a63c;
        transform: translateY(-1px);
    }
    
    .btn-danger:hover {
        transform: translateY(-1px);
    }
    
    .btn-action {
        margin: 0 2px;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .table {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 0 1px #e9ecef;
    }
    
    .table thead th {
        background-color: #2c3e50;
        color: white;
        border: none;
        padding: 1rem;
        font-weight: 600;
    }
    
    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-color: #e9ecef;
    }
    
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0,0,0,.02);
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.04);
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-weight: 500;
    }
    
    .bg-success {
        background-color: #28a745 !important;
    }
    
    .bg-primary {
        background-color: #2c3e50 !important;
    }
    
    .alert {
        border-radius: 8px;
        border: none;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }
    
    .invalid-feedback { 
        display: block;
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    .text-center {
        text-align: center;
    }
    
    .pagination {
        margin-bottom: 0;
    }
    
    .page-link {
        color: #2c3e50;
        border-color: #dee2e6;
    }
    
    .page-item.active .page-link {
        background-color: #2c3e50;
        border-color: #2c3e50;
    }
    
    @media (max-width: 768px) {
        .container {
            padding: 1rem;
            margin-top: 1rem;
        }
        
        .form-section, .table-section {
            padding: 1.5rem;
        }
        
        .btn {
            padding: 0.5rem 1rem;
        }
    }
    .btn-back-dashboard {
        background-color: #2c3e50;
        border-color: #2c3e50;
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
    
    .btn-back-dashboard i {
        font-size: 0.9rem;
        transition: transform 0.3s ease;
    }
    
    .btn-back-dashboard:hover i {
        transform: translateX(-3px);
    }
    
    
    .container .btn-back-dashboard {
        border: 1px solid #2c3e50;
    }
    
    
    @media (max-width: 768px) {
        .btn-back-dashboard {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
    }
</style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Quản lý Người Dùng</h1>
         <a href="{{ route('admin.dashboard') }}" class="btn-back-dashboard">
        <i class="fas fa-arrow-left"></i> Quay lại Dashboard
        </a>

        {{-- Hiện thông báo lỗi validation --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Vui lòng sửa các lỗi sau:</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Phần form thêm/sửa -->
        <div class="form-section">
            <h4 id="form-title">Thêm Người Dùng Mới</h4>

            {{-- Template cho input _method (PUT) để JS chèn vào khi cần) --}}
            <template id="method-put-template">
                @method('PUT')
            </template>

            <form id="userForm" method="POST" action="{{ route('admin.nguoidung.store') }}">
                @csrf
                <div id="method-field"></div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="HoTen" class="form-label">Họ tên *</label>
                            <input type="text" class="form-control" id="HoTen" name="HoTen" required
                                   value="{{ old('HoTen') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="SoDienThoai" class="form-label">Số điện thoại *</label>
                            <input type="text" class="form-control" id="SoDienThoai" name="SoDienThoai" required maxlength="15"
                                   value="{{ old('SoDienThoai') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="Email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="Email" name="Email" required
                                   value="{{ old('Email') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="LoaiNguoiDung" class="form-label">Loại người dùng *</label>
                            <select class="form-select" id="LoaiNguoiDung" name="LoaiNguoiDung" required>
                                <option value="">Chọn loại người dùng</option>
                                <option value="KhachHang" {{ old('LoaiNguoiDung') == 'KhachHang' ? 'selected' : '' }}>Khách hàng</option>
                                <option value="NhanVien" {{ old('LoaiNguoiDung') == 'NhanVien' ? 'selected' : '' }}>Nhân viên</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-secondary" id="btn-cancel" style="display: none;">Hủy</button>
                    <button type="submit" class="btn btn-primary" id="btn-submit">Thêm Người Dùng</button>
                </div>
            </form>
        </div>

        <!-- Phần hiển thị danh sách -->
        <div class="table-section">
            <h4>Danh sách Người Dùng</h4>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Mã ND</th>
                            <th>Họ tên</th>
                            <th>Số điện thoại</th>
                            <th>Email</th>
                            <th>Loại ND</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nguoiDungs as $nguoiDung)
                        <tr>
                            <td>{{ $nguoiDung->MaNguoiDung }}</td>
                            <td>{{ $nguoiDung->HoTen }}</td>
                            <td>{{ $nguoiDung->SoDienThoai }}</td>
                            <td>{{ $nguoiDung->Email }}</td>
                            <td>
                                <span class="badge {{ $nguoiDung->LoaiNguoiDung == 'KhachHang' ? 'bg-success' : 'bg-primary' }}">
                                    {{ $nguoiDung->LoaiNguoiDung == 'KhachHang' ? 'Khách hàng' : 'Nhân viên' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm btn-action btn-edit" 
                                        data-id="{{ $nguoiDung->MaNguoiDung }}">
                                    <i class="fas fa-edit"></i> Sửa
                                </button>
                                <form action="{{ route('admin.nguoidung.destroy', $nguoiDung->MaNguoiDung) }}" 
                                      method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-action" 
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Chưa có người dùng nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(method_exists($nguoiDungs, 'links'))
                <div class="d-flex justify-content-center mt-3">
                    {{ $nguoiDungs->links() }}
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('userForm');
            const formTitle = document.getElementById('form-title');
            const btnSubmit = document.getElementById('btn-submit');
            const btnCancel = document.getElementById('btn-cancel');
            const methodField = document.getElementById('method-field');
            const methodTemplate = document.getElementById('method-put-template');

            // URLs from Blade
            const baseUrl = "{{ url('admin/nguoidung') }}"; // /admin/nguoidung
            const storeUrl = "{{ route('admin.nguoidung.store') }}";

            // Ensure initial action
            form.action = storeUrl;

            // Edit buttons
            document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', function() {
        const userId = this.getAttribute('data-id');
        console.log('Editing user ID:', userId);

        // Hiển thị loading
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tải...';
        this.disabled = true;

        fetch(`${baseUrl}/${userId}/edit`, { 
            headers: { 
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            } 
        })
        .then(response => {
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                // Nếu response không ok, thử parse lỗi từ JSON
                return response.json().then(err => { 
                    throw new Error(err.error || `HTTP error! status: ${response.status}`); 
                }).catch(() => {
                    throw new Error(`HTTP error! status: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            // Khôi phục trạng thái nút
            this.innerHTML = originalText;
            this.disabled = false;

            // Kiểm tra nếu có lỗi trong data
            if (data.error) {
                throw new Error(data.error);
            }

            console.log('User data received:', data);

            // Điền dữ liệu vào form
            document.getElementById('HoTen').value = data.HoTen || '';
            document.getElementById('SoDienThoai').value = data.SoDienThoai || '';
            document.getElementById('Email').value = data.Email || '';
            document.getElementById('LoaiNguoiDung').value = data.LoaiNguoiDung || '';

            // Chuyển form sang chế độ sửa
            form.action = `${baseUrl}/${userId}`;
            methodField.innerHTML = methodTemplate.innerHTML;
            formTitle.textContent = 'Sửa Thông Tin Người Dùng';
            btnSubmit.textContent = 'Cập nhật';
            btnCancel.style.display = 'inline-block';

            // Scroll to form
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
        })
        .catch(error => {
            // Khôi phục trạng thái nút
            this.innerHTML = originalText;
            this.disabled = false;
            
            console.error('Fetch error:', error);
            alert('Lỗi khi lấy dữ liệu: ' + error.message);
        });
    });
});

            // Hủy - reset về trạng thái thêm mới
            btnCancel.addEventListener('click', function() {
                form.reset();
                form.action = storeUrl;
                methodField.innerHTML = '';
                formTitle.textContent = 'Thêm Người Dùng Mới';
                btnSubmit.textContent = 'Thêm Người Dùng';
                btnCancel.style.display = 'none';
            });

            // Optional: client-side minimal validation before submit (HTML5 required already)
            form.addEventListener('submit', function(e) {
                // Example: ensure LoaiNguoiDung is selected
                const loai = document.getElementById('LoaiNguoiDung').value;
                if (!loai) {
                    e.preventDefault();
                    alert('Vui lòng chọn loại người dùng');
                    return;
                }
                // Nút submit sẽ gửi form bình thường — server sẽ trả về errors nếu có.
            });
        });
    </script>
</body>
</html>
