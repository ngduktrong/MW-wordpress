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
        .container {
            max-width: 1200px;
        }
        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .table-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .btn-action {
            margin: 0 2px;
        }
        .invalid-feedback { display:block; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Quản lý Người Dùng</h1>

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

                    fetch(`${baseUrl}/${userId}/edit`, { headers: { 'Accept': 'application/json' } })
                        .then(response => {
                            if (!response.ok) throw new Error('Không thể lấy dữ liệu người dùng');
                            return response.json();
                        })
                        .then(data => {
                            // Điền dữ liệu vào form (override old values)
                            document.getElementById('HoTen').value = data.HoTen || '';
                            document.getElementById('SoDienThoai').value = data.SoDienThoai || '';
                            document.getElementById('Email').value = data.Email || '';
                            document.getElementById('LoaiNguoiDung').value = data.LoaiNguoiDung || '';

                            // chuyển form sang chế độ sửa
                            form.action = `${baseUrl}/${userId}`;
                            // chèn _method PUT từ template (tránh inject Blade trực tiếp trong JS)
                            methodField.innerHTML = methodTemplate.innerHTML;
                            formTitle.textContent = 'Sửa Thông Tin Người Dùng';
                            btnSubmit.textContent = 'Cập nhật';
                            btnCancel.style.display = 'inline-block';

                            // Scroll to form
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        })
                        .catch(error => {
                            console.error(error);
                            alert('Lỗi khi lấy dữ liệu. Vui lòng thử lại.');
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
