<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiểm tra vé sắp chiếu - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        margin: 0;
    }

    h5 {
        color: white !important;
        font-weight: 500;
        margin: 0;
    }

    /* Nút quay lại Dashboard */
    .btn-outline-secondary {
        border-color: var(--accent-color);
        color: var(--secondary-color);
        transition: all 0.3s ease;
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

    .bg-primary { background-color: var(--primary-color) !important; }
    .bg-info { background-color: var(--info-color) !important; }

    /* Button styling */
    .btn {
        border-radius: 6px;
        font-weight: 500;
        padding: 0.8rem 1.5rem;
        transition: all 0.2s ease;
        border: none;
        font-size: 1.1rem;
    }

    .btn-success {
        background-color: var(--success-color);
        border-color: var(--success-color);
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #218838;
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

    .btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    /* Alert styling */
    .alert {
        border-radius: 6px;
        border: 1px solid transparent;
        padding: 1rem 1.25rem;
    }

    .alert-info {
        background-color: #d1ecf1;
        border-color: #bee5eb;
        color: #0c5460;
    }

    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffecb5;
        color: #664d03;
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

    /* Badge styling */
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
        font-weight: 500;
    }

    .bg-success { background-color: var(--success-color) !important; }
    .bg-primary { background-color: var(--primary-color) !important; }
    .bg-secondary { background-color: var(--accent-color) !important; }

    /* Text styling */
    .text-primary {
        color: var(--primary-color) !important;
    }

    .text-success {
        color: var(--success-color) !important;
    }

    .text-warning {
        color: var(--warning-color) !important;
    }

    .text-muted {
        color: #6c757d !important;
    }

    /* Border utilities */
    .border-bottom {
        border-bottom: 1px solid var(--border-color) !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container {
            padding: 0 15px;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .btn {
            width: 100%;
            margin-bottom: 1rem;
        }
        
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }
        
        .d-flex.justify-content-between h1 {
            text-align: center;
        }
        
        .table-responsive {
            border: 1px solid var(--border-color);
            border-radius: 4px;
        }
    }

    /* Focus states for accessibility */
    .btn:focus {
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.25);
    }

    /* Margin utilities */
    .mt-4 {
        margin-top: 1.5rem !important;
    }

    .mb-4 {
        margin-bottom: 1.5rem !important;
    }

    .mb-0 {
        margin-bottom: 0 !important;
    }

    .pb-2 {
        padding-bottom: 0.5rem !important;
    }

    /* Icon spacing */
    .fas {
        margin-right: 0.5rem;
    }

    .me-2 {
        margin-right: 0.5rem !important;
    }

    /* Spinner */
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
    }

    /* Text sizing */
    .fs-5 {
        font-size: 1.25rem !important;
    }

    .fs-6 {
        font-size: 1rem !important;
    }

    /* Custom button padding for larger buttons */
    .py-3 {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
    }

    /* Time section styling */
    .card-body .row .col-md-6:first-child {
        display: block; /* Hiển thị lại phần thời gian hiện tại */
    }

    /* Time display styling */
    #current-time {
        font-family: 'Courier New', monospace;
        font-weight: bold;
    }
</style>
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="text-primary">
                        <i class="fas fa-clock"></i> Kiểm tra vé sắp chiếu
                    </h1>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại Dashboard
                    </a>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông tin thời gian kiểm tra</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Thời gian hiện tại:</strong><br>
                                <span id="current-time" class="fs-5 text-success">{{ $now->format('d/m/Y H:i:s') }}</span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Khoảng thời gian kiểm tra:</strong><br>
                                <span class="fs-5 text-warning">Trong vòng 1 tiếng tới</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-cogs"></i> Chức năng quản lý</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <button id="btn-check-ve" class="btn btn-success w-100 py-3">
                                    <i class="fas fa-search me-2"></i> Kiểm tra vé sắp chiếu
                                </button>
                                <div class="text-muted text-center mt-2">
                                    <small>Hiển thị danh sách vé sẽ chiếu trong 1 giờ tới</small>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <button id="btn-notify" class="btn btn-warning w-100 py-3">
                                    <i class="fas fa-bell me-2"></i> Thông báo đến khách hàng
                                </button>
                                <div class="text-muted text-center mt-2">
                                    <small>Gửi thông báo reminder cho khách hàng</small>
                                </div>
                            </div>
                        </div>

                        <div id="result-section" class="mt-4" style="display: none;">
                            <h5 class="border-bottom pb-2"><i class="fas fa-list me-2"></i>Kết quả kiểm tra:</h5>
                            <div id="result-content" class="alert alert-info mt-3"></div>
                        </div>

                        <div id="ve-list" class="mt-4" style="display: none;">
                            <h5 class="border-bottom pb-2"><i class="fas fa-ticket-alt me-2"></i>Danh sách vé sắp chiếu:</h5>
                            <div id="ve-list-content" class="mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Cập nhật thời gian hiện tại mỗi giây
            function updateCurrentTime() {
                const now = new Date();
                const formatted = now.toLocaleString('vi-VN', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                $('#current-time').text(formatted);
            }
            setInterval(updateCurrentTime, 1000);

            // Kiểm tra vé sắp chiếu
            $('#btn-check-ve').click(function() {
                $.ajax({
                    url: '{{ route("admin.kiemtra.danhsach") }}',
                    type: 'GET',
                    beforeSend: function() {
                        $('#btn-check-ve').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Đang kiểm tra...');
                    },
                    success: function(response) {
                        $('#btn-check-ve').prop('disabled', false).html('<i class="fas fa-search me-2"></i> Kiểm tra vé sắp chiếu');
                        
                        if (response.success) {
                            $('#result-section').show();
                            $('#result-content').html(`
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Tìm thấy:</strong> <span class="badge bg-success fs-6">${response.count} vé</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Thời gian kiểm tra:</strong><br>
                                        Từ <span class="text-primary">${response.thoi_gian_kiem_tra.bat_dau}</span><br>
                                        Đến <span class="text-primary">${response.thoi_gian_kiem_tra.ket_thuc}</span>
                                    </div>
                                </div>
                            `);
                            
                            if (response.ves.length > 0) {
                                $('#ve-list').show();
                                let html = `
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Mã vé</th>
                                                <th>Phim</th>
                                                <th>Suất chiếu</th>
                                                <th>Phòng</th>
                                                <th>Ghế</th>
                                                <th>Khách hàng</th>
                                                <th>Giá vé</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;
                                
                                response.ves.forEach(ve => {
                                    const showTime = new Date(ve.suat_chieu.NgayGioChieu).toLocaleString('vi-VN');
                                    const tenPhim = ve.suat_chieu.phim ? ve.suat_chieu.phim.TenPhim : 'N/A';
                                    const tenPhong = ve.suat_chieu.phong_chieu ? ve.suat_chieu.phong_chieu.TenPhong : ve.MaPhong;
                                    const maKhachHang = ve.hoa_don ? ve.hoa_don.MaKhachHang : 'N/A';
                                    
                                    html += `<tr>
                                        <td><span class="badge bg-primary">${ve.MaVe}</span></td>
                                        <td>${tenPhim}</td>
                                        <td>${showTime}</td>
                                        <td>${tenPhong}</td>
                                        <td><span class="badge bg-secondary">${ve.SoGhe}</span></td>
                                        <td>${maKhachHang}</td>
                                        <td>${ve.GiaVe.toLocaleString('vi-VN')}đ</td>
                                    </tr>`;
                                });
                                
                                html += '</tbody></table></div>';
                                $('#ve-list-content').html(html);
                            } else {
                                $('#ve-list').hide();
                                $('#ve-list-content').html('<div class="alert alert-warning">Không có vé nào sắp chiếu trong khoảng thời gian này.</div>');
                            }
                        }
                    },
                    error: function(xhr) {
                        $('#btn-check-ve').prop('disabled', false).html('<i class="fas fa-search me-2"></i> Kiểm tra vé sắp chiếu');
                        let errorMessage = 'Có lỗi xảy ra khi kiểm tra vé sắp chiếu';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                    }
                });
            });

            // Gửi thông báo đến khách hàng
            $('#btn-notify').click(function() {
                if (!confirm('Bạn có chắc muốn gửi thông báo đến tất cả khách hàng có vé sắp chiếu trong 1 giờ tới?')) {
                    return;
                }

                $.ajax({
                    url: '{{ route("admin.kiemtra.thongbao") }}',
                    type: 'GET',
                    beforeSend: function() {
                        $('#btn-notify').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Đang gửi thông báo...');
                    },
                    success: function(response) {
                        $('#btn-notify').prop('disabled', false).html('<i class="fas fa-bell me-2"></i> Thông báo đến khách hàng');
                        
                        if (response.success) {
                            $('#result-section').show();
                            $('#result-content').removeClass('alert-info').addClass('alert-success').html(`
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-check-circle me-2"></i>Thông báo đã được gửi!</h6>
                                        <strong>Số khách hàng:</strong> ${response.thongBaoCount}<br>
                                        <strong>Số vé:</strong> ${response.veCount}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Thời gian kiểm tra:</strong><br>
                                        Từ <span class="text-primary">${response.thoi_gian_kiem_tra.bat_dau}</span><br>
                                        Đến <span class="text-primary">${response.thoi_gian_kiem_tra.ket_thuc}</span>
                                    </div>
                                </div>
                                <hr>
                                <p class="mb-0">${response.message}</p>
                            `);
                        }
                    },
                    error: function(xhr) {
                        $('#btn-notify').prop('disabled', false).html('<i class="fas fa-bell me-2"></i> Thông báo đến khách hàng');
                        let errorMessage = 'Có lỗi xảy ra khi gửi thông báo';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                    }
                });
            });
        });
    </script>
</body>
</html>