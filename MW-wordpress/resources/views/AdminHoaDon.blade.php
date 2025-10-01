<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Hóa đơn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .is-invalid {
            border-color: #dc3545 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6.4.4.4-.4'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
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
        // Hàm hiển thị lỗi validation
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

        // Hàm reset lỗi
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

        // Thêm hóa đơn
        document.getElementById('formThemHoaDon').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Reset lỗi trước khi gửi
            resetErrors();
            
            fetch('/hoadon', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    MaNhanVien: document.getElementById('MaNhanVien').value || null,
                    MaKhachHang: document.getElementById('MaKhachHang').value || null,
                    TongTien: document.getElementById('TongTien').value
                })
            })
            .then(response => {
                if (!response.ok) {
                    // Nếu response không ok, parse lỗi
                    return response.json().then(errorData => {
                        throw errorData;
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Tạo hóa đơn thành công! Mã hóa đơn: ' + data.MaHoaDon);
                    location.reload();
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.errors) {
                    // Hiển thị lỗi validation
                    displayErrors(error.errors);
                } else {
                    alert('Lỗi: ' + (error.message || 'Có lỗi xảy ra'));
                }
            });
        });

        // Các hàm tìm kiếm và thống kê
        function searchByMaKH() {
            const maKH = document.getElementById('searchMaKH').value;
            if (!maKH) return alert('Vui lòng nhập mã khách hàng');
            
            fetch(`/hoadon/khachhang/${maKH}`)
                .then(response => response.json())
                .then(data => updateTable(data));
        }

        function searchByNgay() {
            const ngay = document.getElementById('searchNgay').value;
            if (!ngay) return alert('Vui lòng chọn ngày');
            
            fetch(`/hoadon/ngay/${ngay}`)
                .then(response => response.json())
                .then(data => updateTable(data));
        }

        function searchByKhoangNgay() {
            const tuNgay = document.getElementById('searchTuNgay').value;
            const denNgay = document.getElementById('searchDenNgay').value;
            
            if (!tuNgay || !denNgay) return alert('Vui lòng chọn khoảng ngày');
            
            fetch('/hoadon/khoangngay', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ tuNgay, denNgay })
            })
            .then(response => response.json())
            .then(data => updateTable(data));
        }

        function thongKeTheoNgay() {
            const ngay = document.getElementById('thongKeNgay').value;
            if (!ngay) return alert('Vui lòng chọn ngày');
            
            fetch(`/hoadon/doanhthu/ngay/${ngay}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('ketQuaThongKe').classList.remove('d-none');
                    document.getElementById('ketQuaThongKe').innerHTML = 
                        `Doanh thu ngày ${data.ngay}: <strong>${Number(data.tongDoanhThu).toLocaleString()} VND</strong>`;
                });
        }

        function thongKeTheoKhoangNgay() {
            const tuNgay = document.getElementById('thongKeTuNgay').value;
            const denNgay = document.getElementById('thongKeDenNgay').value;
            
            if (!tuNgay || !denNgay) return alert('Vui lòng chọn khoảng ngày');
            
            fetch('/hoadon/doanhthu/khoangngay', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ tuNgay, denNgay })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('ketQuaThongKe').classList.remove('d-none');
                document.getElementById('ketQuaThongKe').innerHTML = 
                    `Doanh thu từ ${data.tuNgay} đến ${data.denNgay}: <strong>${Number(data.tongDoanhThu).toLocaleString()} VND</strong>`;
            });
        }

        function updateTable(data) {
            const tbody = document.getElementById('tbodyHoaDon');
            tbody.innerHTML = '';
            
            data.forEach(hoadon => {
                const row = `
                    <tr id="row-${hoadon.MaHoaDon}">
                        <td>${hoadon.MaHoaDon}</td>
                        <td>${hoadon.MaNhanVien || 'N/A'}</td>
                        <td>${hoadon.MaKhachHang || 'N/A'}</td>
                        <td>${hoadon.NgayLap}</td>
                        <td>${Number(hoadon.TongTien).toLocaleString()}</td>
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

        // Xóa hóa đơn
        function deleteHoaDon(maHoaDon) {
            if (!confirm('Bạn có chắc muốn xóa hóa đơn này?')) return;
            
            fetch(`/hoadon/${maHoaDon}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Xóa thành công!');
                    document.getElementById(`row-${maHoaDon}`).remove();
                } else {
                    alert('Lỗi: ' + data.message);
                }
            });
        }

        // Đồng bộ ngày lập
        function syncNgayLap(maHoaDon) {
            fetch(`/hoadon/capnhatngaylap/${maHoaDon}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Đồng bộ thành công!');
                    location.reload();
                } else {
                    alert('Lỗi: ' + data.message);
                }
            });
        }
    </script>
</body>
</html>