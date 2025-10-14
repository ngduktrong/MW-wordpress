<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Vé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

    .container-fluid {
        padding: 20px;
        max-width: 1400px;
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

    h5 {
        color: white !important;
        font-weight: 500;
        margin: 0;
    }

    /* Nút quay lại Dashboard và reset */
    .btn-outline-secondary {
        border-color: var(--accent-color);
        color: var(--secondary-color);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
        margin-right: 10px;
        padding: 0.5rem 1.2rem;
        font-weight: 500;
    }

    .btn-outline-secondary:hover {
        background-color: var(--secondary-color);
        color: white;
        border-color: var(--secondary-color);
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        margin-bottom: 1.5rem;
        padding: 0.5rem 1.2rem;
        font-weight: 500;
    }

    .btn-primary:hover {
        background-color: #1a252f;
        border-color: #1a252f;
    }

    /* Alert container */
    #alertContainer {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
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
    .bg-secondary { background-color: var(--secondary-color) !important; }
    .bg-info { background-color: var(--info-color) !important; }

    /* Form elements */
    .form-label {
        font-weight: 500;
        color: var(--secondary-color);
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 1px solid var(--border-color);
        border-radius: 4px;
        padding: 0.6rem 0.75rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 0.2rem rgba(127, 140, 141, 0.25);
    }

    /* Validation styles */
    .is-invalid {
        border-color: var(--danger-color) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6.4.4.4-.4'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .invalid-feedback {
        display: block;
        color: var(--danger-color);
        font-size: 0.875em;
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

    .btn-success {
        background-color: var(--success-color);
        border-color: var(--success-color);
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #218838;
    }

    .btn-outline-primary {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-outline-warning {
        border-color: var(--warning-color);
        color: var(--warning-color);
    }

    .btn-outline-warning:hover {
        background-color: var(--warning-color);
        color: #212529;
    }

    .btn-outline-success {
        border-color: var(--success-color);
        color: var(--success-color);
    }

    .btn-outline-success:hover {
        background-color: var(--success-color);
        color: white;
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
        margin: 0 2px;
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
    .bg-warning { background-color: var(--warning-color) !important; color: #212529 !important; }
    .bg-danger { background-color: var(--danger-color) !important; }
    .bg-secondary { background-color: var(--accent-color) !important; }

    /* Alert styling */
    .alert {
        border-radius: 6px;
        border: 1px solid transparent;
        padding: 0.75rem 1.25rem;
        margin: 15px 0;
    }

    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c2c7;
        color: #842029;
    }

    .alert-info {
        background-color: #d1ecf1;
        border-color: #bee5eb;
        color: #0c5460;
    }

    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffecb5;
        color: #664d03;
    }

    /* Input group */
    .input-group .btn {
        border-radius: 0 4px 4px 0;
    }

    /* Search results */
    #ketQuaThongKe, #ketQuaGheDaDat {
        margin-top: 1rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 15px;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
        
        .btn-outline-secondary, .btn-primary {
            margin-right: 0;
        }
        
        .table-responsive {
            border: 1px solid var(--border-color);
            border-radius: 4px;
        }
    }

    /* Focus states for accessibility */
    .btn:focus,
    .form-control:focus {
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.25);
    }

    /* Margin utilities */
    .mt-4 {
        margin-top: 1.5rem !important;
    }

    .mb-3 {
        margin-bottom: 1rem !important;
    }

    .mb-0 {
        margin-bottom: 0 !important;
    }

    /* Icon spacing */
    .fas {
        margin-right: 0.5rem;
    }
</style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mt-4">QUẢN LÝ VÉ</h1>
            </div>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại Dashboard
        </a>
        <a href="{{ url('/admin/ve') }}" class="btn btn-primary">nút reset trang vé</a>
        <div id="alertContainer"></div>

        <!-- Form thêm vé -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Thêm vé mới</h5>
                    </div>
                    <div class="card-body">
                        <form id="formThemVe">
                            @csrf
                            <div class="mb-3">
                                <label for="MaSuatChieu" class="form-label">Mã suất chiếu *</label>
                                <input type="number" class="form-control" id="MaSuatChieu" name="MaSuatChieu" required>
                                <div class="invalid-feedback" id="error-MaSuatChieu"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="SoGhe" class="form-label">Số ghế *</label>
                                <input type="text" class="form-control" id="SoGhe" name="SoGhe" maxlength="5" required>
                                <div class="invalid-feedback" id="error-SoGhe"></div>
                            </div>
                            <div class="mb-3">
                                <label for="MaHoaDon" class="form-label">Mã hóa đơn</label>
                                <input type="number" class="form-control" id="MaHoaDon" name="MaHoaDon">
                                <div class="invalid-feedback" id="error-MaHoaDon"></div>
                            </div>
                            <div class="mb-3">
                                <label for="GiaVe" class="form-label">Giá vé *</label>
                                <input type="number" step="0.01" class="form-control" id="GiaVe" name="GiaVe" required>
                                <div class="invalid-feedback" id="error-GiaVe"></div>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Tạo vé
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tìm kiếm & Thống kê -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-search"></i> Tìm kiếm & Thống kê</h5>
                    </div>
                    <div class="card-body">
                        <!-- Tìm kiếm theo mã hóa đơn -->
                        <div class="mb-3">
                            <label class="form-label">Tìm theo mã hóa đơn</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="searchMaHD">
                                <button class="btn btn-outline-primary" onclick="searchByMaHD()">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        

                        <!-- Ghế đã đặt theo suất chiếu -->
                        <div class="mb-3">
                            <label class="form-label">Ghế đã đặt theo suất chiếu</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="searchMaSC">
                                <button class="btn btn-outline-warning" onclick="searchGheDaDat()">
                                    <i class="fas fa-chair"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Thống kê -->
                        <div class="mb-3">
                            <button class="btn btn-outline-success w-100" onclick="thongKeVeDaThanhToan()">
                                <i class="fas fa-chart-pie"></i> Thống kê vé đã thanh toán
                            </button>
                        </div>

                        <div id="ketQuaThongKe" class="alert alert-info d-none"></div>
                        <div id="ketQuaGheDaDat" class="alert alert-warning d-none"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách vé -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Danh sách vé</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã vé</th>
                                        <th>Suất chiếu</th>
                                        <th>Phòng</th>
                                        <th>Ghế</th>
                                        <th>Hóa đơn</th>
                                        <th>Giá vé</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày đặt</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyVe">
                                    @foreach($ves as $ve)
                                    <tr id="row-{{ $ve->MaVe }}">
                                        <td>{{ $ve->MaVe }}</td>
                                        <td>{{ $ve->MaSuatChieu }}</td>
                                        <td>{{ $ve->MaPhong }}</td>
                                        <td>{{ $ve->SoGhe }}</td>
                                        <td>{{ $ve->MaHoaDon ?? 'N/A' }}</td>
                                        <td>{{ number_format($ve->GiaVe, 2) }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($ve->TrangThai == 'paid') bg-success
                                                @elseif($ve->TrangThai == 'pending') bg-warning
                                                @elseif($ve->TrangThai == 'cancelled') bg-danger
                                                @else bg-secondary @endif">
                                                {{ $ve->TrangThai }}
                                            </span>
                                        </td>
                                        <td>{{ $ve->NgayDat ?? 'N/A' }}</td>
                                        <td>
                                            <button class="btn btn-danger btn-sm" onclick="deleteVe({{ $ve->MaVe }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @if($ve->TrangThai != 'paid')
                                            <button class="btn btn-success btn-sm" onclick="thanhToanVe({{ $ve->MaVe }})">
                                                <i class="fas fa-money-bill"></i>
                                            </button>
                                            @endif
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // ============================
    // HÀM DEBUG VÀ XỬ LÝ RESPONSE
    // ============================
    
    function debugResponse(response) {
        console.log('Response status:', response.status);
        console.log('Response URL:', response.url);
        console.log('Response headers:', response.headers);
        
        return response.text().then(text => {
            console.log('Raw response:', text);
            try {
                const jsonData = JSON.parse(text);
                console.log('Parsed JSON:', jsonData);
                return jsonData;
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                return { 
                    success: false, 
                    message: 'Server returned non-JSON response',
                    html: text 
                };
            }
        });
    }

    // ============================
    // HÀM HIỂN THỊ VÀ ẨN ALERT
    // ============================
    
    function showAlert(message, type = 'info') {
        const alertContainer = document.getElementById('alertContainer');
        alertContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        setTimeout(() => {
            hideAlert();
        }, 5000);
    }

    function hideAlert() {
        const alertContainer = document.getElementById('alertContainer');
        alertContainer.innerHTML = '';
    }

    // ============================
    // HÀM XỬ LÝ VALIDATION ERRORS
    // ============================
    
    function displayErrors(errors) {
        for (const [field, messages] of Object.entries(errors)) {
            const errorElement = document.getElementById(`error-${field}`);
            const inputElement = document.getElementById(field);
            
            if (errorElement && inputElement) {
                errorElement.textContent = messages[0];
                inputElement.classList.add('is-invalid');
            }
        }
    }

    function resetErrors() {
        document.querySelectorAll('.is-invalid').forEach(element => {
            element.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(element => {
            element.textContent = '';
        });
    }

    // Reset lỗi khi người dùng bắt đầu nhập
    document.querySelectorAll('#formThemVe input').forEach(input => {
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                this.classList.remove('is-invalid');
                const errorElement = document.getElementById(`error-${this.id}`);
                if (errorElement) {
                    errorElement.textContent = '';
                }
            }
        });
    });

    // ============================
    // XỬ LÝ FORM THÊM VÉ
    // ============================
    
    document.getElementById('formThemVe').addEventListener('submit', function(e) {
    e.preventDefault();
    
    resetErrors();
    hideAlert();
    
    const formData = new FormData();
    const maSuatChieu = document.getElementById('MaSuatChieu').value;
    const soGhe = document.getElementById('SoGhe').value;
    const maHoaDon = document.getElementById('MaHoaDon').value;
    const giaVe = document.getElementById('GiaVe').value;

    // Validate cơ bản
    if (!maSuatChieu || !soGhe || !giaVe) {
        showAlert('Vui lòng điền đầy đủ các trường bắt buộc', 'danger');
        return;
    }

    if (giaVe <= 0) {
        showAlert('Giá vé phải lớn hơn 0', 'danger');
        return;
    }

    formData.append('MaSuatChieu', maSuatChieu);
    formData.append('SoGhe', soGhe);
    formData.append('GiaVe', giaVe);
    if (maHoaDon) formData.append('MaHoaDon', maHoaDon);
    formData.append('_token', '{{ csrf_token() }}');

    console.log('Sending ve data:', { 
        MaSuatChieu: maSuatChieu, 
        SoGhe: soGhe, 
        MaHoaDon: maHoaDon, 
        GiaVe: giaVe 
    });

    // Hiển thị loading
    const submitBtn = document.querySelector('#formThemVe button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    submitBtn.disabled = true;

    fetch('/admin/ve', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        // Kiểm tra nếu response là HTML (có thể là trang login)
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('text/html')) {
            return response.text().then(html => {
                console.error('Server returned HTML instead of JSON:', html.substring(0, 500));
                throw new Error('Server trả về trang HTML. Có thể do lỗi xác thực.');
            });
        }
        
        return response.json();
    })
    .then(data => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        console.log('Response data:', data);
        
        if (data.success) {
            showAlert('Tạo vé thành công! Mã vé: ' + (data.ve ? data.ve.MaVe : 'N/A'), 'success');
            document.getElementById('formThemVe').reset();
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            if (data.errors) {
                // Hiển thị lỗi validation cụ thể
                displayErrors(data.errors);
                showAlert('Có lỗi xảy ra khi tạo vé. Vui lòng kiểm tra lại thông tin.', 'danger');
            } else {
                showAlert('Lỗi: ' + (data.message || 'Không thể tạo vé'), 'danger');
            }
        }
    })
    .catch(error => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        console.error('Fetch error:', error);
        
        if (error.message.includes('HTML')) {
            showAlert('Lỗi xác thực: Bạn cần đăng nhập lại hoặc kiểm tra quyền truy cập.', 'danger');
        } else {
            showAlert('Lỗi kết nối: ' + error.message, 'danger');
        }
    });
});

    // ============================
    // CÁC HÀM TÌM KIẾM
    // ============================
    
    function searchByMaHD() {
        const maHD = document.getElementById('searchMaHD').value;
        if (!maHD) {
            showAlert('Vui lòng nhập mã hóa đơn', 'warning');
            return;
        }
        
        console.log('Searching by MaHD:', maHD);
        
        fetch(`/admin/ve/hoadon/${maHD}`)
            .then(debugResponse)
            .then(data => {
                if (Array.isArray(data)) {
                    updateTable(data);
                    showAlert(`Tìm thấy ${data.length} vé`, 'success');
                } else {
                    showAlert('Không tìm thấy vé nào', 'info');
                    updateTable([]);
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                showAlert('Lỗi tìm kiếm: ' + error.message, 'danger');
            });
    }

    function searchGheDaDat() {
        const maSC = document.getElementById('searchMaSC').value;
        if (!maSC) {
            showAlert('Vui lòng nhập mã suất chiếu', 'warning');
            return;
        }
        
        console.log('Searching booked seats for show:', maSC);
        
        fetch(`/admin/ve/suatchieu/${maSC}`)
            .then(debugResponse)
            .then(data => {
                const ketQuaGheDaDat = document.getElementById('ketQuaGheDaDat');
                ketQuaGheDaDat.classList.remove('d-none');
                
                if (Array.isArray(data) && data.length > 0) {
                    ketQuaGheDaDat.innerHTML = 
                        `Ghế đã đặt cho suất chiếu ${maSC}: <strong>${data.join(', ')}</strong>`;
                    ketQuaGheDaDat.className = 'alert alert-warning';
                } else {
                    ketQuaGheDaDat.innerHTML = `Không có ghế nào được đặt cho suất chiếu ${maSC}`;
                    ketQuaGheDaDat.className = 'alert alert-info';
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                showAlert('Lỗi tìm kiếm: ' + error.message, 'danger');
            });
    }

    // ============================
    // CÁC HÀM THỐNG KÊ
    // ============================
    
    function thongKeVeDaThanhToan() {
        console.log('Getting paid tickets stats...');
        
        fetch('/admin/ve/thongke/sovedathanhtoan')
            .then(debugResponse)
            .then(data => {
                const ketQuaThongKe = document.getElementById('ketQuaThongKe');
                ketQuaThongKe.classList.remove('d-none');
                
                if (data.soVeDaThanhToan !== undefined) {
                    ketQuaThongKe.innerHTML = 
                        `Số vé đã thanh toán: <strong>${data.soVeDaThanhToan}</strong>`;
                    ketQuaThongKe.className = 'alert alert-success';
                } else {
                    ketQuaThongKe.innerHTML = `Không có dữ liệu thống kê`;
                    ketQuaThongKe.className = 'alert alert-info';
                }
            })
            .catch(error => {
                console.error('Stats error:', error);
                showAlert('Lỗi thống kê: ' + error.message, 'danger');
            });
    }

    // ============================
    // CẬP NHẬT TABLE
    // ============================
    
    function updateTable(data) {
        const tbody = document.getElementById('tbodyVe');
        
        if (!data || data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="9" class="text-center">Không có dữ liệu</td></tr>';
            return;
        }
        
        tbody.innerHTML = '';
        
        data.forEach(ve => {
            const row = `
                <tr id="row-${ve.MaVe}">
                    <td>${ve.MaVe}</td>
                    <td>${ve.MaSuatChieu}</td>
                    <td>${ve.MaPhong}</td>
                    <td>${ve.SoGhe}</td>
                    <td>${ve.MaHoaDon || 'N/A'}</td>
                    <td>${Number(ve.GiaVe).toLocaleString('vi-VN')}</td>
                    <td>
                        <span class="badge 
                            ${ve.TrangThai == 'paid' ? 'bg-success' : 
                              ve.TrangThai == 'pending' ? 'bg-warning' : 
                              ve.TrangThai == 'cancelled' ? 'bg-danger' : 'bg-secondary'}">
                            ${ve.TrangThai}
                        </span>
                    </td>
                    <td>${formatDate(ve.NgayDat)}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="deleteVe(${ve.MaVe})">
                            <i class="fas fa-trash"></i>
                        </button>
                        ${ve.TrangThai != 'paid' ? 
                            `<button class="btn btn-success btn-sm" onclick="thanhToanVe(${ve.MaVe})">
                                <i class="fas fa-money-bill"></i>
                            </button>` : ''}
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return date.toLocaleDateString('vi-VN') + ' ' + date.toLocaleTimeString('vi-VN');
    }

    // ============================
    // XÓA VÉ
    // ============================
    
    function deleteVe(maVe) {
        if (!confirm('Bạn có chắc muốn xóa vé này?')) return;
        
        console.log('Deleting ve:', maVe);
        
        fetch(`/admin/ve/${maVe}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(debugResponse)
        .then(data => {
            if (data.success) {
                showAlert('Xóa vé thành công!', 'success');
                document.getElementById(`row-${maVe}`).remove();
                
                const tbody = document.getElementById('tbodyVe');
                if (tbody.children.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="9" class="text-center">Không có dữ liệu</td></tr>';
                }
            } else {
                showAlert('Lỗi: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            showAlert('Có lỗi xảy ra khi xóa vé', 'danger');
        });
    }

    // ============================
    // THANH TOÁN VÉ
    // ============================
    
    function thanhToanVe(maVe) {
        if (!confirm('Xác nhận thanh toán vé này?')) return;
        
        console.log('Paying ve:', maVe);
        
        fetch(`/admin/ve/thanhtoan/${maVe}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(debugResponse)
        .then(data => {
            if (data.success) {
                showAlert('Thanh toán vé thành công!', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showAlert('Lỗi: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Payment error:', error);
            showAlert('Có lỗi xảy ra khi thanh toán vé', 'danger');
        });
    }

    // ============================
    // RESET TRANG
    // ============================
    
    function resetPage() {
        console.log('Resetting page...');
        location.reload();
    }

    // Gán sự kiện cho nút reset
    document.addEventListener('DOMContentLoaded', function() {
        const resetBtn = document.querySelector('a[href*="/admin/ve"]');
        if (resetBtn) {
            resetBtn.addEventListener('click', function(e) {
                e.preventDefault();
                resetPage();
            });
        }
    });
    

    console.log('Ve JavaScript loaded successfully');
</script>
</body>
</html>