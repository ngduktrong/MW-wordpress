<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Vé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mt-4">QUẢN LÝ VÉ</h1>
            </div>
        </div>

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
                            </div>
                            <div class="mb-3">
                                <label for="MaPhong" class="form-label">Mã phòng *</label>
                                <input type="number" class="form-control" id="MaPhong" name="MaPhong" required>
                            </div>
                            <div class="mb-3">
                                <label for="SoGhe" class="form-label">Số ghế *</label>
                                <input type="text" class="form-control" id="SoGhe" name="SoGhe" maxlength="5" required>
                            </div>
                            <div class="mb-3">
                                <label for="MaHoaDon" class="form-label">Mã hóa đơn</label>
                                <input type="number" class="form-control" id="MaHoaDon" name="MaHoaDon">
                            </div>
                            <div class="mb-3">
                                <label for="GiaVe" class="form-label">Giá vé *</label>
                                <input type="number" step="0.01" class="form-control" id="GiaVe" name="GiaVe" required>
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
                                            <button class="btn btn-warning btn-sm" onclick="editVe({{ $ve->MaVe }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
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

    <!-- Modal sửa vé -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa vé</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formSuaVe">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editMaVe" name="MaVe">
                        <div class="mb-3">
                            <label for="editMaSuatChieu" class="form-label">Mã suất chiếu *</label>
                            <input type="number" class="form-control" id="editMaSuatChieu" name="MaSuatChieu" required>
                        </div>
                        <div class="mb-3">
                            <label for="editMaPhong" class="form-label">Mã phòng *</label>
                            <input type="number" class="form-control" id="editMaPhong" name="MaPhong" required>
                        </div>
                        <div class="mb-3">
                            <label for="editSoGhe" class="form-label">Số ghế *</label>
                            <input type="text" class="form-control" id="editSoGhe" name="SoGhe" maxlength="5" required>
                        </div>
                        <div class="mb-3">
                            <label for="editMaHoaDon" class="form-label">Mã hóa đơn</label>
                            <input type="number" class="form-control" id="editMaHoaDon" name="MaHoaDon">
                        </div>
                        <div class="mb-3">
                            <label for="editGiaVe" class="form-label">Giá vé *</label>
                            <input type="number" step="0.01" class="form-control" id="editGiaVe" name="GiaVe" required>
                        </div>
                        <div class="mb-3">
                            <label for="editTrangThai" class="form-label">Trạng thái *</label>
                            <select class="form-control" id="editTrangThai" name="TrangThai" required>
                                <option value="available">Available</option>
                                <option value="booked">Booked</option>
                                <option value="paid">Paid</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" onclick="updateVe()">Lưu thay đổi</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Thêm vé
        document.getElementById('formThemVe').addEventListener('submit', function(e) {
            e.preventDefault();
            
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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Tạo vé thành công!');
                    location.reload();
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
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
                            <button class="btn btn-warning btn-sm" onclick="editVe(${ve.MaVe})">
                                <i class="fas fa-edit"></i>
                            </button>
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

        // Sửa vé
        function editVe(maVe) {
            fetch(`/ve/${maVe}`)
                .then(response => response.json())
                .then(ve => {
                    document.getElementById('editMaVe').value = ve.MaVe;
                    document.getElementById('editMaSuatChieu').value = ve.MaSuatChieu;
                    document.getElementById('editMaPhong').value = ve.MaPhong;
                    document.getElementById('editSoGhe').value = ve.SoGhe;
                    document.getElementById('editMaHoaDon').value = ve.MaHoaDon || '';
                    document.getElementById('editGiaVe').value = ve.GiaVe;
                    document.getElementById('editTrangThai').value = ve.TrangThai;
                    
                    new bootstrap.Modal(document.getElementById('editModal')).show();
                });
        }

        function updateVe() {
            const maVe = document.getElementById('editMaVe').value;
            
            fetch(`/ve/${maVe}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    MaSuatChieu: document.getElementById('editMaSuatChieu').value,
                    MaPhong: document.getElementById('editMaPhong').value,
                    SoGhe: document.getElementById('editSoGhe').value,
                    MaHoaDon: document.getElementById('editMaHoaDon').value || null,
                    GiaVe: document.getElementById('editGiaVe').value,
                    TrangThai: document.getElementById('editTrangThai').value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Cập nhật thành công!');
                    location.reload();
                } else {
                    alert('Lỗi: ' + data.message);
                }
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