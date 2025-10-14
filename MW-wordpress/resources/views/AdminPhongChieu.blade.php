<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Phòng Chiếu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    /* Tông màu chủ đạo đen trắng cổ điển */
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #34495e;
        --accent-color: #7f8c8d;
        --light-color: #ecf0f1;
        --dark-color: #2c3e50;
        --success-color: #28a745;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --border-color: #dee2e6;
    }

    body {
        background-color: #f8f9fa;
        color: #333;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Header và tiêu đề */
    h2 {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--accent-color);
    }

    h5 {
        color: white !important; /* Đảm bảo chữ luôn trắng trên nền đen */
        font-weight: 500;
        margin: 0;
    }

    /* Nút quay lại Dashboard */
    .btn-outline-secondary {
        border-color: var(--accent-color);
        color: var(--secondary-color);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
        padding: 0.5rem 1.2rem;
        font-weight: 500;
    }

    .btn-outline-secondary:hover {
        background-color: var(--secondary-color);
        color: white;
        border-color: var(--secondary-color);
    }

    /* Card styling */
    .card {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
        background: white;
    }

    .card-header {
        background-color: var(--primary-color);
        color: white;
        border-bottom: 1px solid var(--border-color);
        padding: 1rem 1.25rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Form elements */
    .form-label {
        font-weight: 500;
        color: var(--secondary-color);
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 1px solid var(--border-color);
        border-radius: 4px;
        padding: 0.6rem 0.75rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 0.2rem rgba(127, 140, 141, 0.25);
    }

    /* Button styling */
    .btn {
        border-radius: 4px;
        font-weight: 500;
        padding: 0.6rem 1.2rem;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background-color: #1a252f;
        border-color: #1a252f;
    }

    .btn-secondary {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
    }

    .btn-secondary:hover {
        background-color: #6c7a7d;
        border-color: #6c7a7d;
    }

    .btn-warning {
        background-color: var(--warning-color);
        border-color: var(--warning-color);
        color: #212529;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #e0a800;
        color: #212529;
    }

    .btn-danger {
        background-color: var(--danger-color);
        border-color: var(--danger-color);
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #c82333;
    }

    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.875rem;
    }

    /* Table styling */
    .table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        margin-bottom: 0;
    }

    .table th {
        background-color: var(--primary-color);
        color: white;
        font-weight: 500;
        border: none;
        padding: 0.85rem 0.75rem;
    }

    .table td {
        padding: 0.75rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.04);
    }

    /* Action buttons */
    .action-buttons {
        white-space: nowrap;
    }

    .action-buttons .btn {
        margin-right: 0.3rem;
    }

    .action-buttons form {
        display: inline-block;
    }

    /* Alert styling */
    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    /* Edit mode */
    .edit-mode {
        background-color: #fff3cd;
        border-left: 4px solid var(--warning-color);
    }

    /* Form title color - ĐÃ SỬA: Đảm bảo chữ luôn hiển thị rõ trên nền card-header */
    .card-header h5 {
        color: white !important;
        font-weight: 600;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container {
            padding: 0 15px;
        }
        
        .action-buttons {
            white-space: normal;
        }
        
        .action-buttons .btn {
            display: block;
            width: 100%;
            margin-bottom: 0.3rem;
        }
        
        .action-buttons form {
            display: block;
            width: 100%;
        }
    }

    /* Focus states for accessibility */
    .btn:focus,
    .form-control:focus,
    .form-select:focus {
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.25);
    }

    /* Margin utilities */
    .mb-4 {
        margin-bottom: 1.5rem !important;
    }

    .mt-4 {
        margin-top: 1.5rem !important;
    }
</style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4">Quản lý Phòng Chiếu</h2>
         <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại Dashboard
                    </a>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form thêm/sửa phòng chiếu -->
        <div class="card mb-4" id="formContainer">
            <div class="card-header">
                <h5 id="formTitle">
                    {{ isset($phongChieu) ? 'Sửa Phòng Chiếu (ID: ' . $phongChieu->MaPhong . ')' : 'Thêm Phòng Chiếu Mới' }}
                </h5>
            </div>
            <div class="card-body">
                <form id="phongChieuForm" method="POST" 
                      action="{{ isset($phongChieu) ? route('admin.phongchieu.update', $phongChieu->MaPhong) : route('admin.phongchieu.store') }}">
                    @csrf
                    @if(isset($phongChieu))
                        @method('PUT')
                        <input type="hidden" name="MaPhong" value="{{ $phongChieu->MaPhong }}">
                    @endif
                    
                    <div class="mb-3">
                        <label for="TenPhong" class="form-label">Tên Phòng</label>
                        <input type="text" class="form-control" id="TenPhong" name="TenPhong" 
                               value="{{ isset($phongChieu) ? $phongChieu->TenPhong : old('TenPhong') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="SoLuongGhe" class="form-label">Số Lượng Ghế</label>
                        <input type="number" class="form-control" id="SoLuongGhe" name="SoLuongGhe" 
                               value="{{ isset($phongChieu) ? $phongChieu->SoLuongGhe : old('SoLuongGhe') }}" min="1" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="LoaiPhong" class="form-label">Loại Phòng</label>
                        <select class="form-select" id="LoaiPhong" name="LoaiPhong" required>
                            <option value="">Chọn loại phòng</option>
                            <option value="2D" {{ (isset($phongChieu) && $phongChieu->LoaiPhong == '2D') ? 'selected' : (old('LoaiPhong') == '2D' ? 'selected' : '') }}>2D</option>
                            <option value="3D" {{ (isset($phongChieu) && $phongChieu->LoaiPhong == '3D') ? 'selected' : (old('LoaiPhong') == '3D' ? 'selected' : '') }}>3D</option>
                            <option value="IMAX" {{ (isset($phongChieu) && $phongChieu->LoaiPhong == 'IMAX') ? 'selected' : (old('LoaiPhong') == 'IMAX' ? 'selected' : '') }}>IMAX</option>
                            <option value="4DX" {{ (isset($phongChieu) && $phongChieu->LoaiPhong == '4DX') ? 'selected' : (old('LoaiPhong') == '4DX' ? 'selected' : '') }}>4DX</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ isset($phongChieu) ? 'Cập nhật' : 'Thêm mới' }}
                    </button>
                    
                    @if(isset($phongChieu))
                        <a href="{{ route('admin.phongchieu.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Danh sách phòng chiếu -->
        <div class="card">
            <div class="card-header">
                <h5>Danh sách Phòng Chiếu</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Mã Phòng</th>
                                <th>Tên Phòng</th>
                                <th>Số Lượng Ghế</th>
                                <th>Loại Phòng</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($phongChieus as $phong)
                            <tr id="row-{{ $phong->MaPhong }}">
                                <td>{{ $phong->MaPhong }}</td>
                                <td>{{ $phong->TenPhong }}</td>
                                <td>{{ $phong->SoLuongGhe }}</td>
                                <td>{{ $phong->LoaiPhong }}</td>
                                <td class="action-buttons">
                                    <a href="{{ route('admin.phongchieu.index', ['edit' => $phong->MaPhong]) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <form action="{{ route('admin.phongchieu.destroy', $phong->MaPhong) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa phòng chiếu này?')">
                                            <i class="fas fa-trash"></i> Xóa
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Kiểm tra nếu URL có tham số edit thì tự động cuộn đến form
            const urlParams = new URLSearchParams(window.location.search);
            const editId = urlParams.get('edit');
            if (editId) {
                document.getElementById('formContainer').scrollIntoView({ behavior: 'smooth' });
                document.getElementById('formContainer').classList.add('edit-mode');
            }
        });
    </script>
</body>
</html>