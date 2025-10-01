<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Vé</title>
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
                <h1 class="text-center mt-4">QUẢN LÝ VÉ</h1>
            </div>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại Dashboard
        </a>

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
                                <label for="MaPhong" class="form-label">Mã phòng *</label>
                                <input type="number" class="form-control" id="MaPhong" name="MaPhong" required>
                                <div class="invalid-feedback" id="error-MaPhong"></div>
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

        // Thêm vé
        document.getElementById('formThemVe').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Reset lỗi trước khi gửi
            resetErrors();
            
            fetch('/ve', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    MaSuatChieu: document.getElementById('MaSuatChieu').value,
                    MaPhong: document.getElementById('MaPhong').value,
                    SoGhe: document.getElementById('SoGhe').value,
                    MaHoaDon: document.getElementById('MaHoaDon').value || null,
                    GiaVe: document.getElementById('GiaVe').value
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
                    alert('Tạo vé thành công!');
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
        function searchByMaHD() {
            const maHD = document.getElementById('searchMaHD').value;
            if (!maHD) return alert('Vui lòng nhập mã hóa đơn');
            
            fetch(`/ve/hoadon/${maHD}`)
                .then(response => response.json())
                .then(data => updateTable(data));
        }

        function searchByMaKH() {
            const maKH = document.getElementById('searchMaKH').value;
            if (!maKH) return alert('Vui lòng nhập mã khách hàng');
            
            fetch(`/ve/khachhang/${maKH}`)
                .then(response => response.json())
                .then(data => updateTable(data));
        }

        function searchGheDaDat() {
            const maSC = document.getElementById('searchMaSC').value;
            if (!maSC) return alert('Vui lòng nhập mã suất chiếu');
            
            fetch(`/ve/suatchieu/${maSC}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('ketQuaGheDaDat').classList.remove('d-none');
                    document.getElementById('ketQuaGheDaDat').innerHTML = 
                        `Ghế đã đặt cho suất chiếu ${maSC}: <strong>${data.join(', ') || 'Không có'}</strong>`;
                });
        }

        function thongKeVeDaThanhToan() {
            fetch('/ve/thongke/sovedathanhtoan')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('ketQuaThongKe').classList.remove('d-none');
                    document.getElementById('ketQuaThongKe').innerHTML = 
                        `Số vé đã thanh toán: <strong>${data.soVeDaThanhToan}</strong>`;
                });
        }

        function updateTable(data) {
            const tbody = document.getElementById('tbodyVe');
            tbody.innerHTML = '';
            
            data.forEach(ve => {
                const row = `
                    <tr id="row-${ve.MaVe}">
                        <td>${ve.MaVe}</td>
                        <td>${ve.MaSuatChieu}</td>
                        <td>${ve.MaPhong}</td>
                        <td>${ve.SoGhe}</td>
                        <td>${ve.MaHoaDon || 'N/A'}</td>
                        <td>${Number(ve.GiaVe).toLocaleString()}</td>
                        <td>
                            <span class="badge 
                                ${ve.TrangThai == 'paid' ? 'bg-success' : 
                                  ve.TrangThai == 'pending' ? 'bg-warning' : 
                                  ve.TrangThai == 'cancelled' ? 'bg-danger' : 'bg-secondary'}">
                                ${ve.TrangThai}
                            </span>
                        </td>
                        <td>${ve.NgayDat || 'N/A'}</td>
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

        // Xóa vé
        function deleteVe(maVe) {
            if (!confirm('Bạn có chắc muốn xóa vé này?')) return;
            
            fetch(`/ve/${maVe}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Xóa thành công!');
                    document.getElementById(`row-${maVe}`).remove();
                } else {
                    alert('Lỗi: ' + data.message);
                }
            });
        }

        // Thanh toán vé
        function thanhToanVe(maVe) {
            if (!confirm('Xác nhận thanh toán vé này?')) return;
            
            fetch(`/ve/thanhtoan/${maVe}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Thanh toán thành công!');
                    location.reload();
                } else {
                    alert('Lỗi: ' + data.message);
                }
            });
        }
    </script>
</body>
</html>