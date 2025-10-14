<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản Lý Khách hàng</title>
    <style>
    /* Tông màu chủ đạo đen trắng cổ điển */
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #34495e;
        --accent-color: #7f8c8d;
        --light-color: #ecf0f1;
        --dark-color: #2c3e50;
        --success-color: #27ae60;
        --warning-color: #f39c12;
        --danger-color: #e74c3c;
        --border-color: #dce1e5;
    }

    body {
        background-color: #f8f9fa;
        color: #333;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
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

    h3 {
        color: var(--secondary-color);
        font-weight: 500;
        margin-bottom: 1.2rem;
    }

    /* Nút quay lại Dashboard */
    .btn-outline-secondary {
        display: inline-block;
        padding: 0.5rem 1.2rem;
        border: 1px solid var(--accent-color);
        color: var(--secondary-color);
        text-decoration: none;
        border-radius: 4px;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
        font-weight: 500;
        background: white;
    }

    .btn-outline-secondary:hover {
        background-color: var(--secondary-color);
        color: white;
        border-color: var(--secondary-color);
        text-decoration: none;
    }

    /* Form section */
    .form-section {
        background: white;
        padding: 25px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-color);
    }

    /* Form elements */
    form > div {
        margin-bottom: 1.2rem;
    }

    label {
        display: block;
        font-weight: 500;
        color: var(--secondary-color);
        margin-bottom: 0.5rem;
    }

    input[type="text"],
    input[type="number"] {
        width: 100%;
        max-width: 300px;
        padding: 0.6rem 0.75rem;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        font-size: 1rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    input[type="text"]:focus,
    input[type="number"]:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 0.2rem rgba(127, 140, 141, 0.25);
        outline: none;
    }

    input:disabled {
        background-color: #f8f9fa;
        opacity: 0.7;
    }

    /* Button styling */
    button {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 4px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-right: 10px;
    }

    button[type="submit"] {
        background-color: var(--success-color);
        color: white;
    }

    button[type="submit"]:hover {
        background-color: #219653;
    }

    #cancel-btn {
        background-color: var(--accent-color);
        color: white;
    }

    #cancel-btn:hover {
        background-color: #6c7a7d;
    }

    .actions button {
        padding: 0.4rem 0.8rem;
        font-size: 0.875rem;
        margin: 0 2px;
    }

    .actions button:first-child {
        background-color: var(--warning-color);
        color: white;
    }

    .actions button:first-child:hover {
        background-color: #e67e22;
    }

    .actions button:last-child {
        background-color: var(--danger-color);
        color: white;
    }

    .actions button:last-child:hover {
        background-color: #c0392b;
    }

    /* Table styling */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    th, td {
        border: 1px solid var(--border-color);
        padding: 0.85rem 0.75rem;
        text-align: left;
    }

    th {
        background-color: var(--primary-color);
        color: white;
        font-weight: 500;
        border-color: var(--primary-color);
    }

    tbody tr:nth-child(even) {
        background-color: rgba(0, 0, 0, 0.02);
    }

    tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.04);
    }

    /* Message styling */
    .error {
        color: var(--danger-color);
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
    }

    .success {
        color: var(--success-color);
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
    }

    /* Loading indicator */
    #loading {
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--primary-color);
        color: white;
        padding: 10px 15px;
        border-radius: 4px;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .hidden {
        display: none;
    }

    .loading {
        opacity: 0.6;
        pointer-events: none;
    }

    /* Pagination */
    .pagination {
        margin-top: 25px;
        text-align: center;
    }

    .pagination a,
    .pagination span {
        display: inline-block;
        padding: 0.5rem 0.9rem;
        margin: 0 3px;
        border: 1px solid var(--border-color);
        text-decoration: none;
        color: var(--secondary-color);
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .pagination a:hover {
        background-color: var(--light-color);
    }

    .pagination span.current {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    /* Horizontal rule */
    hr {
        border: none;
        border-top: 1px solid var(--border-color);
        margin: 25px 0;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        body {
            padding: 15px;
        }
        
        .form-section {
            padding: 15px;
        }
        
        input[type="text"],
        input[type="number"] {
            max-width: 100%;
        }
        
        table {
            display: block;
            overflow-x: auto;
        }
        
        .actions button {
            display: block;
            width: 100%;
            margin-bottom: 5px;
        }
    }

   
    button:focus,
    input:focus {
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.25);
    }
</style>
</head>
<body>
    <h1>Quản Lý Khách hàng</h1>
     <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại Dashboard
                    </a>

    <!-- Loading Indicator -->
    <div id="loading" class="hidden">Đang xử lý...</div>

    <div class="form-section">
        <h3 id="form-title">Thêm khách hàng mới</h3>
        
        <form id="kh-form">
            <input type="hidden" id="form-mode" value="create"> <!-- create / edit -->

            <div>
                <label for="MaNguoiDung">Mã Người Dùng</label>
                <input type="text" id="MaNguoiDung" name="MaNguoiDung" />
                <span id="ma-msg" class="error"></span>
            </div>

            <div>
                <label for="DiemTichLuy">Điểm tích lũy</label>
                <input type="number" id="DiemTichLuy" name="DiemTichLuy" value="0" />
            </div>

            <div>
                <button type="submit">Lưu</button>
                <button type="button" id="cancel-btn">Hủy</button>
            </div>
        </form>
    </div>

    <hr>

    <h3>Danh sách khách hàng</h3>
    <table id="kh-table">
        <thead>
            <tr>
                <th>Mã KH</th>
                <th>Họ tên</th>
                <th>Số điện thoại</th>
                <th>Email</th>
                <th>Điểm tích lũy</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($khachhangs as $kh)
            <tr>
                <td>{{ $kh->MaNguoiDung }}</td>
                <td>{{ $kh->nguoiDung->HoTen ?? 'N/A' }}</td>
                <td>{{ $kh->nguoiDung->SoDienThoai ?? 'N/A' }}</td>
                <td>{{ $kh->nguoiDung->Email ?? 'N/A' }}</td>
                <td>{{ $kh->DiemTichLuy }}</td>
                <td class="actions">
                    <button onclick="setEditMode({{ $kh->MaNguoiDung }}, {{ $kh->DiemTichLuy }})">Sửa</button>
                    <button onclick="deleteKhachHang({{ $kh->MaNguoiDung }})">Xóa</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Phân trang -->
    <div class="pagination">
        {{ $khachhangs->links() }}
    </div>

    <script>
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const maEl = document.getElementById('MaNguoiDung');
        const diemEl = document.getElementById('DiemTichLuy');
        const maMsg = document.getElementById('ma-msg');
        const formModeEl = document.getElementById('form-mode');
        const formTitleEl = document.getElementById('form-title');
        const loadingEl = document.getElementById('loading');

        let currentValidMa = null;
        let checkTimeout = null;

        // Thêm debounce cho kiểm tra mã người dùng
        maEl.addEventListener('input', function() {
            clearTimeout(checkTimeout);
            checkTimeout = setTimeout(checkMaNguoiDung, 500); // Chờ 500ms sau khi ngừng gõ
        });

        // Vẫn giữ sự kiện blur để kiểm tra ngay lập tức khi rời khỏi trường
        maEl.addEventListener('blur', function() {
            clearTimeout(checkTimeout);
            checkMaNguoiDung();
        });

        function showLoading() {
            loadingEl.classList.remove('hidden');
            document.body.classList.add('loading');
        }

        function hideLoading() {
            loadingEl.classList.add('hidden');
            document.body.classList.remove('loading');
        }

        async function checkMaNguoiDung() {
            const ma = maEl.value.trim();
            const isEdit = formModeEl.value === 'edit';
            
            if (!ma) {
                maMsg.textContent = '';
                currentValidMa = null;
                return;
            }

            showLoading();

            try {
                // Thêm tham số is_edit để phân biệt create vs edit
                const url = `/admin/khach-hang/check/${encodeURIComponent(ma)}${isEdit ? '?is_edit=true' : ''}`;
                const res = await fetch(url, {
                    headers: { 'Accept': 'application/json' }
                });

                // Nếu server trả redirect hoặc trang login (HTML), thông báo rõ ràng
                if (res.status === 401 || res.status === 302) {
                    maMsg.textContent = 'Bạn chưa đăng nhập hoặc không có quyền.';
                    currentValidMa = null;
                    return;
                }

                const contentType = res.headers.get('content-type') || '';
                if (!contentType.includes('application/json')) {
                    const text = await res.text();
                    console.error('Unexpected response (not JSON):', res.status, text);
                    maMsg.textContent = 'Lỗi server: phản hồi không phải JSON.';
                    currentValidMa = null;
                    return;
                }

                const data = await res.json();

                if (res.ok && data.valid) {
                    // Hiển thị thông báo thành công
                    maMsg.textContent = data.data.HoTen ? ('OK — ' + data.data.HoTen) : 'Mã hợp lệ.';
                    maMsg.classList.remove('error');
                    maMsg.classList.add('success');
                    currentValidMa = data.data.MaNguoiDung;
                } else {
                    maMsg.textContent = data.message || 'Mã người dùng không hợp lệ.';
                    maMsg.classList.remove('success');
                    maMsg.classList.add('error');
                    currentValidMa = null;
                }

            } catch (err) {
                console.error(err);
                maMsg.textContent = 'Lỗi hệ thống khi kiểm tra mã người dùng.';
                currentValidMa = null;
            } finally {
                hideLoading();
            }
        }

        // Submit form (create/update)
        document.getElementById('kh-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const mode = formModeEl.value;
            const ma = maEl.value.trim();
            const diem = diemEl.value;

            if (!ma) {
                maMsg.textContent = 'Vui lòng nhập Mã Người Dùng';
                maEl.focus();
                return;
            }

            // Nếu muốn bắt buộc phải check thành công trước khi submit
            if (!currentValidMa || String(currentValidMa) !== String(ma)) {
                maMsg.textContent = 'Vui lòng kiểm tra và xác nhận Mã Người Dùng hợp lệ trước khi lưu.';
                maEl.focus();
                return;
            }

            showLoading();

            try {
                let url = '/admin/khach-hang';
                let method = 'POST';

                if (mode === 'edit') {
                    url = `/admin/khach-hang/${encodeURIComponent(ma)}`;
                    method = 'PUT';
                }

                const res = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        MaNguoiDung: parseInt(ma),
                        DiemTichLuy: parseInt(diem) || 0
                    })
                });

                const contentType = res.headers.get('content-type') || '';
                if (!contentType.includes('application/json')) {
                    const text = await res.text();
                    console.error('Unexpected response (not JSON):', res.status, text);
                    alert('Lỗi server khi lưu.');
                    return;
                }

                const data = await res.json();

                if (res.ok && data.success) {
                    alert(data.message || 'Lưu thành công');
                    // reload trang để cập nhật danh sách
                    location.reload();
                } else {
                    alert(data.message || 'Lưu thất bại');
                }

            } catch (err) {
                console.error(err);
                alert('Lỗi hệ thống khi lưu.');
            } finally {
                hideLoading();
            }
        });

        // Hàm chuyển sang chế độ chỉnh sửa
        function setEditMode(maNguoiDung, diemTichLuy) {
            formModeEl.value = 'edit';
            formTitleEl.textContent = 'Chỉnh sửa khách hàng';
            
            maEl.value = maNguoiDung;
            diemEl.value = diemTichLuy;
            maEl.disabled = true; // Vô hiệu hóa mã khi chỉnh sửa
            
            // Reset thông báo và kiểm tra lại với mode edit
            maMsg.textContent = '';
            currentValidMa = maNguoiDung;
            checkMaNguoiDung();
        }

        // Hàm xóa khách hàng
        async function deleteKhachHang(maNguoiDung) {
            if (!confirm('Bạn có chắc chắn muốn xóa khách hàng này?')) {
                return;
            }

            showLoading();

            try {
                const res = await fetch(`/admin/khach-hang/${encodeURIComponent(maNguoiDung)}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                });

                const contentType = res.headers.get('content-type') || '';
                if (!contentType.includes('application/json')) {
                    const text = await res.text();
                    console.error('Unexpected response (not JSON):', res.status, text);
                    alert('Lỗi server khi xóa.');
                    return;
                }

                const data = await res.json();

                if (res.ok && data.success) {
                    alert(data.message || 'Xóa thành công');
                    location.reload();
                } else {
                    alert(data.message || 'Xóa thất bại');
                }

            } catch (err) {
                console.error(err);
                alert('Lỗi hệ thống khi xóa.');
            } finally {
                hideLoading();
            }
        }

        // Nút hủy
        document.getElementById('cancel-btn').addEventListener('click', function() {
            resetForm();
        });

        // Hàm reset form
        function resetForm() {
            maEl.value = '';
            diemEl.value = 0;
            maMsg.textContent = '';
            formModeEl.value = 'create';
            formTitleEl.textContent = 'Thêm khách hàng mới';
            maEl.disabled = false;
            currentValidMa = null;
        }
    </script>
</body>
</html>