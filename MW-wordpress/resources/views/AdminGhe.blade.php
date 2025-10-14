<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Ghế</title>
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
        --info-color: #17a2b8;
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
    h1 {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--accent-color);
    }

    h3, h4 {
        color: var(--secondary-color);
        font-weight: 500;
        margin-bottom: 1.2rem;
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

    /* Form section */
    .form-section {
        background: white;
        padding: 25px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-color);
    }

    /* Alert styling */
    .alert {
        border-radius: 6px;
        border: 1px solid transparent;
        padding: 0.75rem 1.25rem;
        margin-top: 1rem;
    }

    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
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

    /* Validation styles */
    .is-invalid {
        border-color: var(--danger-color);
    }

    .invalid-feedback {
        display: block;
        color: var(--danger-color);
        font-size: 0.875rem;
        margin-top: 0.25rem;
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

    .btn-info {
        background-color: var(--info-color);
        border-color: var(--info-color);
    }

    .btn-info:hover {
        background-color: #138496;
        border-color: #138496;
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
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
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

    /* Horizontal rule */
    hr {
        border: none;
        border-top: 1px solid var(--border-color);
        margin: 1.5rem 0;
    }

    /* Text utilities */
    .text-center {
        text-align: center;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container {
            padding: 0 15px;
        }
        
        .form-section {
            padding: 15px;
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
        
        .table-responsive {
            border: 1px solid var(--border-color);
            border-radius: 4px;
        }
        
        .d-flex.align-items-end {
            margin-top: 1rem;
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
    .mb-3 {
        margin-bottom: 1rem !important;
    }

    .mb-4 {
        margin-bottom: 1.5rem !important;
    }

    .me-2 {
        margin-right: 0.5rem !important;
    }

    .py-4 {
        padding-top: 1.5rem !important;
        padding-bottom: 1.5rem !important;
    }

    /* Icon spacing */
    .fas {
        margin-right: 0.5rem;
    }
</style>
</head>
<body>
<div class="container py-4">
    <h1 class="text-center mb-4">Quản lý Ghế</h1>
     <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Quay lại Dashboard
     </a>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="form-section">
        <h3>{{ isset($editingGhe) && $editingGhe ? 'Sửa Ghế' : 'Thêm Ghế' }}</h3>
        <form method="POST" action="{{ isset($editingGhe) && $editingGhe ? route('ghe.update', [$editingGhe->MaPhong, $editingGhe->SoGhe]) : route('ghe.store') }}">
            @csrf
            @if(isset($editingGhe) && $editingGhe)
                @method('PUT')
            @else
                <input type="hidden" name="mode" value="single">
            @endif

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="MaPhong" class="form-label">Phòng</label>
                    <select class="form-select" name="MaPhong" required {{ (isset($editingGhe) && $editingGhe) ? 'disabled' : '' }}>
                        <option value="">Chọn phòng</option>
                        @foreach($phongChieus as $phong)
                            <option value="{{ $phong->MaPhong }}"
                                {{ (isset($editingGhe) && $editingGhe && $editingGhe->MaPhong == $phong->MaPhong) ? 'selected' : (old('MaPhong') == $phong->MaPhong ? 'selected' : '') }}>
                                {{ $phong->TenPhong }} ({{ $phong->SoLuongGhe }} ghế)
                            </option>
                        @endforeach
                    </select>
                    @if(isset($editingGhe) && $editingGhe)
                        <input type="hidden" name="MaPhong" value="{{ $editingGhe->MaPhong }}">
                    @endif
                </div>

                <div class="col-md-4">
                    <label for="SoGhe" class="form-label">Số Ghế</label>
                    <input type="text"
                           class="form-control @error('SoGhe') is-invalid @enderror"
                           name="SoGhe"
                           value="{{ old('SoGhe', isset($editingGhe) && $editingGhe ? $editingGhe->SoGhe : '') }}"
                           required
                           maxlength="5"
                           pattern="^[A-Z][A-Za-z0-9]{0,4}$"
                           title="Mã ghế phải bắt đầu bằng chữ in hoa (A-Z), chỉ chứa chữ/số, tối đa 5 ký tự. Ví dụ: A01, B10">
                    @error('SoGhe')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">{{ isset($editingGhe) && $editingGhe ? 'Cập nhật' : 'Thêm' }}</button>
                    @if(isset($editingGhe) && $editingGhe)
                        <a href="{{ route('ghe.index') }}" class="btn btn-secondary">Hủy</a>
                    @endif
                </div>
            </div>
        </form>

        <hr>

        <h4>Thêm hàng loạt</h4>
        <form method="POST" action="{{ route('ghe.store') }}">
            @csrf
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Phòng</label>
                    <select class="form-select" name="MaPhong" required>
                        <option value="">Chọn phòng</option>
                        @foreach($phongChieus as $phong)
                            <option value="{{ $phong->MaPhong }}" {{ old('MaPhong') == $phong->MaPhong ? 'selected' : '' }}>
                                {{ $phong->TenPhong }} ({{ $phong->SoLuongGhe }} ghế)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Số lượng</label>
                    <input type="number" class="form-control" name="quantity" min="1" value="{{ old('quantity') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Ghế/hàng</label>
                    <input type="number" class="form-control" name="seats_per_row" min="1" max="99" value="{{ old('seats_per_row', 10) }}">
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-info" name="mode" value="bulk">Thêm hàng loạt</button>
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
            <tr>
                <th>Mã Phòng</th>
                <th>Tên Phòng</th>
                <th>Số Ghế</th>
                <th>Loại Phòng</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            @forelse($ghes as $ghe)
                <tr>
                    <td>{{ $ghe->MaPhong }}</td>
                    <td>{{ optional($ghe->phongChieu)->TenPhong }}</td>
                    <td>{{ $ghe->SoGhe }}</td>
                    <td>{{ optional($ghe->phongChieu)->LoaiPhong }}</td>
                    <td class="action-buttons">
                        <a href="{{ route('ghe.edit', [$ghe->MaPhong, $ghe->SoGhe]) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                        <form action="{{ route('ghe.destroy', [$ghe->MaPhong, $ghe->SoGhe]) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Xóa ghế này?')">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Chưa có dữ liệu ghế</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
