<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Ghế</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .container { max-width: 1200px; }
        .table-responsive { overflow-x: auto; }
        .action-buttons { white-space: nowrap; }
        .form-section { background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .alert { margin-top: 20px; }
    </style>
</head>
<body>
<div class="container py-4">
    <h1 class="text-center mb-4">Quản lý Ghế</h1>

    <!-- Thông báo -->
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

    <!-- Form thêm / sửa -->
    <div class="form-section">
        <h3>{{ isset($editingGhe) ? 'Sửa Ghế' : 'Thêm Ghế' }}</h3>
        <form method="POST"
              action="{{ isset($editingGhe) ? route('ghe.update', [$editingGhe->MaPhong, $editingGhe->SoGhe]) : route('ghe.store') }}">
            @csrf
            @if(isset($editingGhe))
                @method('PUT')
            @endif

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="MaPhong" class="form-label">Mã Phòng</label>
                    <select class="form-select" id="MaPhong" name="MaPhong" required>
                        <option value="">Chọn phòng</option>
                        @foreach($phongChieus as $phong)
                            <option value="{{ $phong->MaPhong }}"
                                {{ (isset($editingGhe) && $editingGhe->MaPhong == $phong->MaPhong) ? 'selected' : '' }}>
                                {{ $phong->TenPhong }} ({{ $phong->MaPhong }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="SoGhe" class="form-label">Số Ghế</label>
                    <input type="text" class="form-control" id="SoGhe" name="SoGhe"
                           value="{{ isset($editingGhe) ? $editingGhe->SoGhe : '' }}" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        {{ isset($editingGhe) ? 'Cập nhật' : 'Thêm' }}
                    </button>
                    @if(isset($editingGhe))
                        <a href="{{ route('ghe.index') }}" class="btn btn-secondary">Hủy</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Danh sách ghế -->
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
                    <td>{{ $ghe->phongChieu->TenPhong }}</td>
                    <td>{{ $ghe->SoGhe }}</td>
                    <td>{{ $ghe->phongChieu->LoaiPhong }}</td>
                    <td class="action-buttons">
                        <a href="{{ route('ghe.edit', [$ghe->MaPhong, $ghe->SoGhe]) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                        <form action="{{ route('ghe.destroy', [$ghe->MaPhong, $ghe->SoGhe]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa ghế này?')">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Không có dữ liệu ghế</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
