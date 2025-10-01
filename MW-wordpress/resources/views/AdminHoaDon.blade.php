<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Hóa đơn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mt-4">QUẢN LÝ HÓA ĐƠN</h1>
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
                            </div>
                            <div class="mb-3">
                                <label for="MaKhachHang" class="form-label">Mã khách hàng</label>
                                <input type="number" class="form-control" id="MaKhachHang" name="MaKhachHang">
                            </div>
                            <div class="mb-3">
                                <label for="TongTien" class="form-label">Tổng tiền</label>
                                <input type="number" step="0.01" class="form-control" id="TongTien" name="TongTien" required>
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
                                            <button class="btn btn-warning btn-sm" onclick="editHoaDon({{ $hoadon->MaHoaDon }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
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

    <!-- Modal sửa hóa đơn -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa hóa đơn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formSuaHoaDon">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editMaHoaDon" name="MaHoaDon">
                        <div class="mb-3">
                            <label for="editMaNhanVien" class="form-label">Mã nhân viên</label>
                            <input type="number" class="form-control" id="editMaNhanVien" name="MaNhanVien">
                        </div>
                        <div class="mb-3">
                            <label for="editMaKhachHang" class="form-label">Mã khách hàng</label>
                            <input type="number" class="form-control" id="editMaKhachHang" name="MaKhachHang">
                        </div>
                        <div class="mb-3">
                            <label for="editTongTien" class="form-label">Tổng tiền</label>
                            <input type="number" step="0.01" class="form-control" id="editTongTien" name="TongTien" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" onclick="updateHoaDon()">Lưu thay đổi</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Thêm hóa đơn
        document.getElementById('formThemHoaDon').addEventListener('submit', function(e) {
            e.preventDefault();
            
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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Tạo hóa đơn thành công! Mã hóa đơn: ' + data.MaHoaDon);
                    location.reload();
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
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
                            <button class="btn btn-warning btn-sm" onclick="editHoaDon(${hoadon.MaHoaDon})">
                                <i class="fas fa-edit"></i>
                            </button>
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

        // Sửa hóa đơn
        function editHoaDon(maHoaDon) {
            fetch(`/hoadon/${maHoaDon}`)
                .then(response => response.json())
                .then(hoadon => {
                    document.getElementById('editMaHoaDon').value = hoadon.MaHoaDon;
                    document.getElementById('editMaNhanVien').value = hoadon.MaNhanVien || '';
                    document.getElementById('editMaKhachHang').value = hoadon.MaKhachHang || '';
                    document.getElementById('editTongTien').value = hoadon.TongTien;
                    
                    new bootstrap.Modal(document.getElementById('editModal')).show();
                });
        }

        function updateHoaDon() {
            const maHoaDon = document.getElementById('editMaHoaDon').value;
            
            fetch(`/hoadon/${maHoaDon}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    MaNhanVien: document.getElementById('editMaNhanVien').value || null,
                    MaKhachHang: document.getElementById('editMaKhachHang').value || null,
                    TongTien: document.getElementById('editTongTien').value
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