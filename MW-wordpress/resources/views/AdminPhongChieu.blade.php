<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Phòng Chiếu</title>
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
            color: "{{ isset($phongChieu) ? '#dc3545' : '#0d6efd' }}";
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4">Quản lý Phòng Chiếu</h2>
        
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