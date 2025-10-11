<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản trị Khách hàng</title>
    <style>
        /* nhỏ gọn cho demo */
        .error { color: red; }
        .success { color: green; }
        .hidden { display: none; }
        form > div { margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .actions button { margin-right: 5px; }
        .form-section { background: #f9f9f9; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .loading { opacity: 0.6; pointer-events: none; }
        #loading { position: fixed; top: 20px; right: 20px; background: #007bff; color: white; padding: 10px; border-radius: 5px; z-index: 1000; }
        .pagination { margin-top: 20px; text-align: center; }
        .pagination a, .pagination span { padding: 8px 16px; margin: 0 4px; border: 1px solid #ddd; text-decoration: none; color: #007bff; }
        .pagination span.current { background: #007bff; color: white; border-color: #007bff; }
    </style>
</head>
<body>
    <h1>Quản trị Khách hàng</h1>
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