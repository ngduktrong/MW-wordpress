<!DOCTYPE html>
<html>
<head>
    <title>Quản lý Phim</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Quản lý Phim</h1>

    <!-- Form thêm/sửa phim -->
    <div id="form-container">
        <h2 id="form-title">Thêm phim mới</h2>
        <form id="phim-form">
            <input type="hidden" id="ma-phim" name="MaPhim">
            
            <div>
                <label>Tên phim:</label>
                <input type="text" id="ten-phim" name="TenPhim" required>
            </div>

            <div>
                <label>Thời lượng (phút):</label>
                <input type="number" id="thoi-luong" name="ThoiLuong" required>
            </div>

            <div>
                <label>Ngày khởi chiếu:</label>
                <input type="date" id="ngay-khoi-chieu" name="NgayKhoiChieu" required>
            </div>

            <div>
                <label>Nước sản xuất:</label>
                <input type="text" id="nuoc-san-xuat" name="NuocSanXuat" required>
            </div>

            <div>
                <label>Định dạng:</label>
                <input type="text" id="dinh-dang" name="DinhDang" required>
            </div>

            <div>
                <label>Mô tả:</label>
                <textarea id="mo-ta" name="MoTa"></textarea>
            </div>

            <div>
                <label>Đạo diễn:</label>
                <input type="text" id="dao-dien" name="DaoDien" required>
            </div>

            <div>
                <label>Poster URL:</label>
                <input type="text" id="poster-url" name="DuongDanPoster">
            </div>

            <button type="submit">Lưu</button>
            <button type="button" id="btn-cancel">Hủy</button>
        </form>
    </div>

    <!-- Danh sách phim -->
    <h2>Danh sách phim</h2>
    <table id="phim-table" border="1">
        <thead>
            <tr>
                <th>Mã phim</th>
                <th>Tên phim</th>
                <th>Thời lượng</th>
                <th>Ngày khởi chiếu</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <!-- Dữ liệu sẽ được thêm bằng JavaScript -->
        </tbody>
    </table>

    <script>
    $(document).ready(function() {
        // Load danh sách phim
        loadPhims();

        // Xử lý submit form
        $('#phim-form').on('submit', function(e) {
            e.preventDefault();
            savePhim();
        });

        // Xử lý nút hủy
        $('#btn-cancel').click(function() {
            resetForm();
        });
    });

    function loadPhims() {
        $.ajax({
            url: '/phims',
            type: 'GET',
            success: function(response) {
                $('#phim-table tbody').empty();
                response.data.forEach(function(phim) {
                    $('#phim-table tbody').append(`
                        <tr>
                            <td>${phim.MaPhim}</td>
                            <td>${phim.TenPhim}</td>
                            <td>${phim.ThoiLuong} phút</td>
                            <td>${phim.NgayKhoiChieu}</td>
                            <td>
                                <button onclick="editPhim(${phim.MaPhim})">Sửa</button>
                                <button onclick="deletePhim(${phim.MaPhim})">Xóa</button>
                            </td>
                        </tr>
                    `);
                });
            }
        });
    }

    function savePhim() {
        const formData = $('#phim-form').serializeArray();
        const data = {};
        formData.forEach(field => {
            data[field.name] = field.value;
        });

        const method = data.MaPhim ? 'PUT' : 'POST';
        const url = data.MaPhim ? `/phims/${data.MaPhim}` : '/phims';

        $.ajax({
            url: url,
            type: method,
            data: data,
            success: function() {
                resetForm();
                loadPhims();
            },
            error: function(xhr) {
                alert('Có lỗi xảy ra: ' + xhr.responseJSON.message);
            }
        });
    }

    function editPhim(maPhim) {
        $.ajax({
            url: `/phims/${maPhim}`,
            type: 'GET',
            success: function(phim) {
                $('#ma-phim').val(phim.MaPhim);
                $('#ten-phim').val(phim.TenPhim);
                $('#thoi-luong').val(phim.ThoiLuong);
                $('#ngay-khoi-chieu').val(phim.NgayKhoiChieu);
                $('#nuoc-san-xuat').val(phim.NuocSanXuat);
                $('#dinh-dang').val(phim.DinhDang);
                $('#mo-ta').val(phim.MoTa);
                $('#dao-dien').val(phim.DaoDien);
                $('#poster-url').val(phim.DuongDanPoster);
                
                $('#form-title').text('Sửa phim');
            }
        });
    }

    function deletePhim(maPhim) {
        if (confirm('Bạn có chắc muốn xóa phim này?')) {
            $.ajax({
                url: `/phims/${maPhim}`,
                type: 'DELETE',
                success: function() {
                    loadPhims();
                },
                error: function(xhr) {
                    alert('Có lỗi xảy ra: ' + xhr.responseJSON.message);
                }
            });
        }
    }

    function resetForm() {
        $('#phim-form')[0].reset();
        $('#ma-phim').val('');
        $('#form-title').text('Thêm phim mới');
    }
    </script>
</body>
</html>