<!DOCTYPE html>
<html lang="vi">
<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản lý nhân viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container-fluid {
            padding: 20px;
        }
        .form-container, .list-container {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .table th {
            background-color: #343a40;
            color: white;
        }
        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        }
    </style>
</head>
<body>
    <div class="alert-container" id="alertContainer"></div>
    
    <div class="container-fluid">
        <h1 class="text-center mb-4">Quản lý nhân viên</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary mb-3">
            ← Quay lại Dashboard
        </a>
        
        <div class="row">
            <!-- Ô thêm/sửa nhân viên -->
            <div class="col-md-6">
                <div class="form-container">
                    <h4 id="form-title">Thêm nhân viên</h4>
                    <form id="nhanvien-form">
                        @csrf
                        <div id="form-fields">
                            <div class="mb-3">
                                <label for="MaNguoiDung" class="form-label">Mã người dùng *</label>
                                <input type="text" class="form-control" id="MaNguoiDung" name="MaNguoiDung" required>
                                <div class="form-text" id="ma-nguoi-dung-check"></div>
                            </div>
                            <div class="mb-3">
                                <label for="ChucVu" class="form-label">Chức vụ *</label>
                                <input type="text" class="form-control" id="ChucVu" name="ChucVu" required>
                            </div>
                            <div class="mb-3">
                                <label for="Luong" class="form-label">Lương *</label>
                                <input type="number" class="form-control" id="Luong" name="Luong" min="0" step="0.01" required>
                            </div>
                            <div class="mb-3">
                                <label for="VaiTro" class="form-label">Vai trò *</label>
                                <select class="form-select" id="VaiTro" name="VaiTro" required>
                                    <option value="">Chọn vai trò</option>
                                    <option value="Admin">Admin</option>
                                    <option value="QuanLy">Quản lý</option>
                                    <option value="ThuNgan">Thu ngân</option>
                                    <option value="BanVe">Bán vé</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success" id="submit-btn">Thêm nhân viên</button>
                        <button type="button" class="btn btn-secondary" id="cancel-btn" style="display: none;">Hủy</button>
                    </form>
                </div>
            </div>

            <!-- Ô danh sách nhân viên -->
            <div class="col-md-6">
                <div class="list-container">
                    <h4>Danh sách nhân viên</h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Mã NV</th>
                                    <th>Họ tên</th>
                                    <th>Chức vụ</th>
                                    <th>Lương</th>
                                    <th>Vai trò</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nhanViens as $nv)
                                <tr>
                                    <td>{{ $nv->MaNguoiDung }}</td>
                                    <td>{{ $nv->nguoiDung->HoTen ?? 'N/A' }}</td>
                                    <td>{{ $nv->ChucVu }}</td>
                                    <td>{{ number_format($nv->Luong, 0, ',', '.') }} VNĐ</td>
                                    <td>
                                        <span class="badge bg-{{ $nv->VaiTro == 'Admin' ? 'danger' : ($nv->VaiTro == 'QuanLy' ? 'warning' : 'info') }}">
                                            {{ $nv->VaiTro }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $nv->MaNguoiDung }}">Sửa</button>
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $nv->MaNguoiDung }}">Xóa</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
    let isEditMode = false;
    let currentEditId = null;

    // CSRF setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Hiển thị thông báo
    function showAlert(message, type = 'success') {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $('#alertContainer').html(alertHtml);
        setTimeout(() => {
            $('.alert').alert('close');
        }, 5000);
    }

    // Kiểm tra mã người dùng tồn tại - SỬA LẠI
    $('#MaNguoiDung').on('blur', function() {
        const maNguoiDung = $(this).val();
        if (maNguoiDung) {
            const checkUrl = '{{ route("admin.nhanvien.check", ["maNguoiDung" => "__ID__"]) }}'.replace('__ID__', maNguoiDung);
            
            $.ajax({
                url: checkUrl,
                type: 'GET',
                success: function(response) {
                    if (response.exists) {
                        if (response.isAlreadyEmployee) {
                            $('#ma-nguoi-dung-check').html('<span class="text-warning">⚠ Người dùng này đã là nhân viên</span>');
                        } else {
                            $('#ma-nguoi-dung-check').html('<span class="text-success">✓ Mã người dùng tồn tại</span>');
                        }
                    } else {
                        $('#ma-nguoi-dung-check').html('<span class="text-danger">✗ Mã người dùng không tồn tại</span>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error checking user:', error);
                    $('#ma-nguoi-dung-check').html('<span class="text-danger">❌ Lỗi kiểm tra mã người dùng</span>');
                }
            });
        } else {
            $('#ma-nguoi-dung-check').empty();
        }
    });

    // Xử lý submit form - SỬA LẠI
    $('#nhanvien-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        let url, method;

        if (isEditMode) {
            url = '{{ route("admin.nhanvien.update", ["id" => "__ID__"]) }}'.replace('__ID__', currentEditId);
            method = 'PUT';
            formData.append('_method', 'PUT');
        } else {
            url = '{{ route("admin.nhanvien.store") }}';
            method = 'POST';
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                showAlert(response.success, 'success');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    if (errors) {
                        let errorMessage = '';
                        for (const field in errors) {
                            errorMessage += errors[field][0] + '\n';
                        }
                        showAlert(errorMessage, 'danger');
                    }
                } else if (xhr.responseJSON && xhr.responseJSON.error) {
                    showAlert(xhr.responseJSON.error, 'danger');
                } else {
                    showAlert('Có lỗi xảy ra! Vui lòng thử lại.', 'danger');
                }
            }
        });
    });

    // Sửa nhân viên - SỬA LẠI
    $('.edit-btn').on('click', function() {
        const id = $(this).data('id');
        const editUrl = '{{ route("admin.nhanvien.edit", ["id" => "__ID__"]) }}'.replace('__ID__', id);
        
        $.ajax({
            url: editUrl,
            type: 'GET',
            success: function(response) {
                if (response.error) {
                    showAlert(response.error, 'danger');
                    return;
                }
                
                isEditMode = true;
                currentEditId = id;
                
                $('#form-title').text('Sửa nhân viên');
                $('#MaNguoiDung').val(response.MaNguoiDung).prop('readonly', true);
                $('#ChucVu').val(response.ChucVu);
                $('#Luong').val(response.Luong);
                $('#VaiTro').val(response.VaiTro);
                $('#submit-btn').text('Cập nhật nhân viên');
                $('#cancel-btn').show();
                $('#ma-nguoi-dung-check').html('<span class="text-info">📝 Chế độ chỉnh sửa</span>');
            },
            error: function(xhr, status, error) {
                console.error('Error loading employee:', error);
                showAlert('Lỗi khi tải thông tin nhân viên!', 'danger');
            }
        });
    });

    // Xóa nhân viên - SỬA LẠI
    $('.delete-btn').on('click', function() {
        if (confirm('Bạn có chắc chắn muốn xóa nhân viên này?')) {
            const id = $(this).data('id');
            const deleteUrl = '{{ route("admin.nhanvien.destroy", ["id" => "__ID__"]) }}'.replace('__ID__', id);
            
            $.ajax({
                url: deleteUrl,
                type: 'POST',
                data: {
                    _method: 'DELETE'
                },
                success: function(response) {
                    showAlert(response.success, 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr, status, error) {
                    console.error('Error deleting employee:', error);
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        showAlert(xhr.responseJSON.error, 'danger');
                    } else {
                        showAlert('Lỗi khi xóa nhân viên!', 'danger');
                    }
                }
            });
        }
    });


        function resetForm() {
            isEditMode = false;
            currentEditId = null;
            $('#form-title').text('Thêm nhân viên');
            $('#nhanvien-form')[0].reset();
            $('#MaNguoiDung').prop('readonly', false);
            $('#submit-btn').text('Thêm nhân viên');
            $('#cancel-btn').hide();
            $('#ma-nguoi-dung-check').empty();
        }
    });
    </script>
</body>
</html>