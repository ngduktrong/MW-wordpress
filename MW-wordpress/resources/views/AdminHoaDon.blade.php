<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Hóa đơn</title>
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

    .btn-info {
        background-color: var(--info-color);
        border-color: var(--info-color);
    }

    .btn-info:hover {
        background-color: #138496;
        border-color: #138496;
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

    /* Input group */
    .input-group .btn {
        border-radius: 0 4px 4px 0;
    }

    /* Search results */
    #ketQuaThongKe {
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
        
        .col-auto .btn {
            width: auto;
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

    /* Text utilities */
    .text-center {
        text-align: center;
    }
</style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mt-4">QUẢN LÝ HÓA ĐƠN</h1>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại Dashboard
                </a>
            </div>
        </div>
        <a href="{{ url('/admin/hoadon') }}" class="btn btn-primary">nút reset trang hóa đơn</a>
        <div id="alertContainer"></div>

        <!-- Form thêm hóa đơn -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Thêm hóa đơn mới</h5>
                    </div>
                    <div class="card-body">
                        <form id="formThemHoaDon">
                            @csrf
                            <div class="mb-3">
                                <label for="MaNhanVien" class="form-label">Mã nhân viên</label>
                                <input type="number" class="form-control" id="MaNhanVien" name="MaNhanVien">
                                <div class="invalid-feedback" id="error-MaNhanVien"></div>
                            </div>
                            <div class="mb-3">
                                <label for="MaKhachHang" class="form-label">Mã khách hàng</label>
                                <input type="number" class="form-control" id="MaKhachHang" name="MaKhachHang">
                                <div class="invalid-feedback" id="error-MaKhachHang"></div>
                            </div>
                            <div class="mb-3">
                                <label for="TongTien" class="form-label">Tổng tiền *</label>
                                <input type="number" step="0.01" class="form-control" id="TongTien" name="TongTien" required>
                                <div class="invalid-feedback" id="error-TongTien"></div>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Tạo hóa đơn
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
                        <!-- Tìm kiếm theo mã khách hàng -->
                        <div class="mb-3">
                            <label class="form-label">Tìm theo mã khách hàng</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="searchMaKH">
                                <button class="btn btn-outline-primary" onclick="searchByMaKH()">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Tìm kiếm theo ngày -->
                        <div class="mb-3">
                            <label class="form-label">Tìm theo ngày lập</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="searchNgay">
                                <button class="btn btn-outline-primary" onclick="searchByNgay()">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Tìm kiếm theo khoảng ngày -->
                        <div class="mb-3">
                            <label class="form-label">Tìm theo khoảng ngày</label>
                            <div class="row">
                                <div class="col">
                                    <input type="date" class="form-control" id="searchTuNgay" placeholder="Từ ngày">
                                </div>
                                <div class="col">
                                    <input type="date" class="form-control" id="searchDenNgay" placeholder="Đến ngày">
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-outline-primary" onclick="searchByKhoangNgay()">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Thống kê doanh thu -->
                        <div class="mb-3">
                            <label class="form-label">Thống kê doanh thu theo ngày</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="thongKeNgay">
                                <button class="btn btn-outline-success" onclick="thongKeTheoNgay()">
                                    <i class="fas fa-chart-bar"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Thống kê doanh thu theo khoảng</label>
                            <div class="row">
                                <div class="col">
                                    <input type="date" class="form-control" id="thongKeTuNgay">
                                </div>
                                <div class="col">
                                    <input type="date" class="form-control" id="thongKeDenNgay">
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-outline-success" onclick="thongKeTheoKhoangNgay()">
                                        <i class="fas fa-chart-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="ketQuaThongKe" class="alert alert-info d-none"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách hóa đơn -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Danh sách hóa đơn</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã HD</th>
                                        <th>Mã NV</th>
                                        <th>Mã KH</th>
                                        <th>Ngày lập</th>
                                        <th>Tổng tiền</th>
                                        <th>Số vé</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyHoaDon">
                                    @foreach($hoadons as $hoadon)
                                    <tr id="row-{{ $hoadon->MaHoaDon }}">
                                        <td>{{ $hoadon->MaHoaDon }}</td>
                                        <td>{{ $hoadon->MaNhanVien ?? 'N/A' }}</td>
                                        <td>{{ $hoadon->MaKhachHang ?? 'N/A' }}</td>
                                        <td>{{ $hoadon->NgayLap }}</td>
                                        <td>{{ number_format($hoadon->TongTien, 2) }}</td>
                                        <td>{{ $hoadon->ves->count() }}</td>
                                        <td>
                                            <button class="btn btn-danger btn-sm" onclick="deleteHoaDon({{ $hoadon->MaHoaDon }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-info btn-sm" onclick="syncNgayLap({{ $hoadon->MaHoaDon }})">
                                                <i class="fas fa-sync"></i>
                                            </button>
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
                // Nếu không phải JSON, trả về text để xử lý
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
        
        // Tự động ẩn alert sau 5 giây
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
    document.querySelectorAll('#formThemHoaDon input').forEach(input => {
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
    // XỬ LÝ FORM THÊM HÓA ĐƠN
    // ============================
    
    document.getElementById('formThemHoaDon').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Reset lỗi trước khi gửi
        resetErrors();
        hideAlert();
        
        const formData = new FormData();
        const maNV = document.getElementById('MaNhanVien').value;
        const maKH = document.getElementById('MaKhachHang').value;
        const tongTien = document.getElementById('TongTien').value;

        // Validate cơ bản
        if (!tongTien || tongTien <= 0) {
            showAlert('Vui lòng nhập tổng tiền hợp lệ', 'danger');
            return;
        }

        formData.append('TongTien', tongTien);
        if (maNV) formData.append('MaNhanVien', maNV);
        if (maKH) formData.append('MaKhachHang', maKH);
        formData.append('_token', '{{ csrf_token() }}');

        console.log('Sending hoadon data:', { 
            MaNhanVien: maNV, 
            MaKhachHang: maKH, 
            TongTien: tongTien 
        });

        fetch('/admin/hoadon', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            return debugResponse(response);
        })
        .then(data => {
            if (data.success) {
                showAlert('Tạo hóa đơn thành công! Mã hóa đơn: ' + data.MaHoaDon, 'success');
                document.getElementById('formThemHoaDon').reset();
                // Tự động reload sau 2 giây
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                if (data.errors) {
                    displayErrors(data.errors);
                } else {
                    showAlert('Lỗi: ' + (data.message || 'Không thể tạo hóa đơn'), 'danger');
                }
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            showAlert('Lỗi kết nối: ' + error.message, 'danger');
        });
    });

    // ============================
    // CÁC HÀM TÌM KIẾM
    // ============================
    
    function searchByMaKH() {
        const maKH = document.getElementById('searchMaKH').value;
        if (!maKH) {
            showAlert('Vui lòng nhập mã khách hàng', 'warning');
            return;
        }
        
        console.log('Searching by MaKH:', maKH);
        
        fetch(`/admin/hoadon/khachhang/${maKH}`)
            .then(debugResponse)
            .then(data => {
                if (Array.isArray(data)) {
                    updateTable(data);
                    showAlert(`Tìm thấy ${data.length} hóa đơn`, 'success');
                } else {
                    showAlert('Không tìm thấy hóa đơn nào', 'info');
                    updateTable([]);
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                showAlert('Lỗi tìm kiếm: ' + error.message, 'danger');
            });
    }

    function searchByNgay() {
        const ngay = document.getElementById('searchNgay').value;
        if (!ngay) {
            showAlert('Vui lòng chọn ngày', 'warning');
            return;
        }
        
        console.log('Searching by date:', ngay);
        
        fetch(`/admin/hoadon/ngay/${ngay}`)
            .then(debugResponse)
            .then(data => {
                if (Array.isArray(data)) {
                    updateTable(data);
                    showAlert(`Tìm thấy ${data.length} hóa đơn ngày ${ngay}`, 'success');
                } else {
                    showAlert('Không tìm thấy hóa đơn nào', 'info');
                    updateTable([]);
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                showAlert('Lỗi tìm kiếm: ' + error.message, 'danger');
            });
    }

    function searchByKhoangNgay() {
        const tuNgay = document.getElementById('searchTuNgay').value;
        const denNgay = document.getElementById('searchDenNgay').value;
        
        if (!tuNgay || !denNgay) {
            showAlert('Vui lòng chọn khoảng ngày đầy đủ', 'warning');
            return;
        }
        
        console.log('Searching by date range:', tuNgay, 'to', denNgay);
        
        fetch('/admin/hoadon/khoangngay', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ tuNgay, denNgay })
        })
        .then(debugResponse)
        .then(data => {
            if (Array.isArray(data)) {
                updateTable(data);
                showAlert(`Tìm thấy ${data.length} hóa đơn từ ${tuNgay} đến ${denNgay}`, 'success');
            } else {
                showAlert('Không tìm thấy hóa đơn nào', 'info');
                updateTable([]);
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
    
    function thongKeTheoNgay() {
        const ngay = document.getElementById('thongKeNgay').value;
        if (!ngay) {
            showAlert('Vui lòng chọn ngày thống kê', 'warning');
            return;
        }
        
        console.log('Revenue stats for date:', ngay);
        
        fetch(`/admin/hoadon/doanhthu/ngay/${ngay}`)
            .then(debugResponse)
            .then(data => {
                const ketQuaThongKe = document.getElementById('ketQuaThongKe');
                ketQuaThongKe.classList.remove('d-none');
                
                if (data.tongDoanhThu !== undefined) {
                    ketQuaThongKe.innerHTML = 
                        `Doanh thu ngày ${data.ngay}: <strong>${Number(data.tongDoanhThu).toLocaleString('vi-VN')} VND</strong>`;
                    ketQuaThongKe.className = 'alert alert-success';
                } else {
                    ketQuaThongKe.innerHTML = `Không có doanh thu ngày ${ngay}`;
                    ketQuaThongKe.className = 'alert alert-info';
                }
            })
            .catch(error => {
                console.error('Stats error:', error);
                showAlert('Lỗi thống kê: ' + error.message, 'danger');
            });
    }

    function thongKeTheoKhoangNgay() {
        const tuNgay = document.getElementById('thongKeTuNgay').value;
        const denNgay = document.getElementById('thongKeDenNgay').value;
        
        if (!tuNgay || !denNgay) {
            showAlert('Vui lòng chọn khoảng ngày thống kê đầy đủ', 'warning');
            return;
        }
        
        console.log('Revenue stats for range:', tuNgay, 'to', denNgay);
        
        fetch('/admin/hoadon/doanhthu/khoangngay', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ tuNgay, denNgay })
        })
        .then(debugResponse)
        .then(data => {
            const ketQuaThongKe = document.getElementById('ketQuaThongKe');
            ketQuaThongKe.classList.remove('d-none');
            
            if (data.tongDoanhThu !== undefined) {
                ketQuaThongKe.innerHTML = 
                    `Doanh thu từ ${data.tuNgay} đến ${data.denNgay}: <strong>${Number(data.tongDoanhThu).toLocaleString('vi-VN')} VND</strong>`;
                ketQuaThongKe.className = 'alert alert-success';
            } else {
                ketQuaThongKe.innerHTML = `Không có doanh thu từ ${tuNgay} đến ${denNgay}`;
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
        const tbody = document.getElementById('tbodyHoaDon');
        
        if (!data || data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center">Không có dữ liệu</td></tr>';
            return;
        }
        
        tbody.innerHTML = '';
        
        data.forEach(hoadon => {
            const row = `
                <tr id="row-${hoadon.MaHoaDon}">
                    <td>${hoadon.MaHoaDon}</td>
                    <td>${hoadon.MaNhanVien || 'N/A'}</td>
                    <td>${hoadon.MaKhachHang || 'N/A'}</td>
                    <td>${formatDate(hoadon.NgayLap)}</td>
                    <td>${Number(hoadon.TongTien).toLocaleString('vi-VN')}</td>
                    <td>${hoadon.ves ? hoadon.ves.length : 0}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="deleteHoaDon(${hoadon.MaHoaDon})">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="btn btn-info btn-sm" onclick="syncNgayLap(${hoadon.MaHoaDon})">
                            <i class="fas fa-sync"></i>
                        </button>
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
    // XÓA HÓA ĐƠN
    // ============================
    
    function deleteHoaDon(maHoaDon) {
        if (!confirm('Bạn có chắc muốn xóa hóa đơn này?')) return;
        
        console.log('Deleting hoadon:', maHoaDon);
        
        fetch(`/admin/hoadon/${maHoaDon}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(debugResponse)
        .then(data => {
            if (data.success) {
                showAlert('Xóa hóa đơn thành công!', 'success');
                document.getElementById(`row-${maHoaDon}`).remove();
                
                // Nếu không còn dòng nào, hiển thị thông báo
                const tbody = document.getElementById('tbodyHoaDon');
                if (tbody.children.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center">Không có dữ liệu</td></tr>';
                }
            } else {
                showAlert('Lỗi: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            showAlert('Có lỗi xảy ra khi xóa hóa đơn', 'danger');
        });
    }

    // ============================
    // ĐỒNG BỘ NGÀY LẬP
    // ============================
    
    function syncNgayLap(maHoaDon) {
        console.log('Syncing date for hoadon:', maHoaDon);
        
        fetch(`/admin/hoadon/capnhatngaylap/${maHoaDon}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(debugResponse)
        .then(data => {
            if (data.success) {
                showAlert('Đồng bộ ngày lập thành công!', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showAlert('Lỗi: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Sync error:', error);
            showAlert('Có lỗi xảy ra khi đồng bộ ngày lập', 'danger');
        });
    }

    // ============================
    // RESET TRANG
    // ============================
    
    function resetPage() {
        console.log('Resetting page...');
        location.reload();
    }

    // Gán sự kiện cho nút reset nếu có
    document.addEventListener('DOMContentLoaded', function() {
        const resetBtn = document.querySelector('a[href*="/admin/hoadon"]');
        if (resetBtn) {
            resetBtn.addEventListener('click', function(e) {
                e.preventDefault();
                resetPage();
            });
        }
    });

    console.log('HoaDon JavaScript loaded successfully');
</script>
</body>
</html>