<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Suất Chiếu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .action-buttons {
            white-space: nowrap;
        }
        .edit-mode {
            background-color: #fff3cd;
        }
        #formTitle {
            color: "{{ isset($suatChieu) ? '#dc3545' : '#0d6efd' }}";
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4">Quản lý Suất Chiếu</h2>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form thêm/sửa suất chiếu -->
        <div class="card mb-4" id="formContainer">
            <div class="card-header">
                <h5 id="formTitle">
                    {{ isset($suatChieu) ? 'Sửa Suất Chiếu (ID: ' . $suatChieu->MaSuatChieu . ')' : 'Thêm Suất Chiếu Mới' }}
                </h5>
            </div>
            <div class="card-body">
                <form id="suatChieuForm" method="POST" 
                      action="{{ isset($suatChieu) ? route('admin.suatchieu.update', $suatChieu->MaSuatChieu) : route('admin.suatchieu.store') }}">
                    @csrf
                    @if(isset($suatChieu))
                        @method('PUT')
                        <input type="hidden" name="MaSuatChieu" value="{{ $suatChieu->MaSuatChieu }}">
                    @endif
                    
                    <div class="mb-3">
                        <label for="MaPhim" class="form-label">Phim</label>
                        <select class="form-select" id="MaPhim" name="MaPhim" required>
                            <option value="">Chọn phim</option>
                            @foreach($phims as $phim)
                                <option value="{{ $phim->MaPhim }}" {{ (isset($suatChieu) && $suatChieu->MaPhim == $phim->MaPhim) ? 'selected' : '' }}>
                                    {{ $phim->TenPhim }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="MaPhong" class="form-label">Phòng Chiếu</label>
                        <select class="form-select" id="MaPhong" name="MaPhong" required>
                            <option value="">Chọn phòng chiếu</option>
                            @foreach($phongChieus as $phong)
                                <option value="{{ $phong->MaPhong }}" {{ (isset($suatChieu) && $suatChieu->MaPhong == $phong->MaPhong) ? 'selected' : '' }}>
                                    {{ $phong->TenPhong }} ({{ $phong->LoaiPhong }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="NgayGioChieu" class="form-label">Ngày và Giờ Chiếu</label>
                        <input type="datetime-local" class="form-control" id="NgayGioChieu" name="NgayGioChieu" 
                               value="{{ isset($suatChieu) ? date('Y-m-d\TH:i', strtotime($suatChieu->NgayGioChieu)) : old('NgayGioChieu') }}" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ isset($suatChieu) ? 'Cập nhật' : 'Thêm mới' }}
                    </button>
                    
                    @if(isset($suatChieu))
                        <a href="{{ route('admin.suatchieu.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Danh sách suất chiếu -->
        <div class="card">
            <div class="card-header">
                <h5>Danh sách Suất Chiếu</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Mã Suất Chiếu</th>
                                <th>Phim</th>
                                <th>Phòng Chiếu</th>
                                <th>Ngày Giờ Chiếu</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suatChieus as $suat)
                            <tr id="row-{{ $suat->MaSuatChieu }}">
                                <td>{{ $suat->MaSuatChieu }}</td>
                                <td>{{ $suat->phim->TenPhim ?? 'N/A' }}</td>
                                <td>{{ $suat->phongChieu->TenPhong ?? 'N/A' }}</td>
                                <td>{{ date('d/m/Y H:i', strtotime($suat->NgayGioChieu)) }}</td>
                                <td class="action-buttons">
                                    <a href="{{ route('admin.suatchieu.index', ['edit' => $suat->MaSuatChieu]) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <form action="{{ route('admin.suatchieu.destroy', $suat->MaSuatChieu) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa suất chiếu này?')">
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