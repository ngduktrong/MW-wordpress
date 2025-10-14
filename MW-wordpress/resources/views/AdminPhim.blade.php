<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quản lý Phim</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

    h2 {
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

    /* Success message */
    [style*="background:#e6ffed"] {
        background: #d4edda !important;
        border: 1px solid #c3e6cb !important;
        color: #155724;
        padding: 12px 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    /* Form container */
    #formBox {
        background: white;
        padding: 25px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-color);
    }

    /* Form elements */
    .form-row {
        display: grid;
        grid-template-columns: 160px 1fr;
        gap: 12px;
        margin-bottom: 1rem;
        align-items: center;
    }

    label {
        font-weight: 500;
        color: var(--secondary-color);
        text-align: right;
    }

    input[type="text"],
    input[type="number"],
    input[type="date"],
    textarea {
        padding: 0.6rem 0.75rem;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        font-size: 1rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        width: 100%;
        box-sizing: border-box;
    }

    input[type="text"]:focus,
    input[type="number"]:focus,
    input[type="date"]:focus,
    textarea:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 0.2rem rgba(127, 140, 141, 0.25);
        outline: none;
    }

    textarea {
        resize: vertical;
        min-height: 80px;
    }

    /* Button styling */
    .btn {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 4px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background-color: #1a252f;
    }

    .btn-secondary {
        background-color: var(--accent-color);
        color: white;
    }

    .btn-secondary:hover {
        background-color: #6c7a7d;
    }

    .btn-danger {
        background-color: var(--danger-color);
        color: white;
    }

    .btn-danger:hover {
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
        vertical-align: middle;
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

    /* Actions column */
    .actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .actions .btn {
        padding: 0.4rem 0.8rem;
        font-size: 0.875rem;
    }

    .actions form {
        margin: 0;
    }

    /* Empty state */
    td[colspan] {
        text-align: center;
        color: var(--accent-color);
        font-style: italic;
        padding: 2rem !important;
    }

    /* Form buttons container */
    [style*="margin-top:8px"] {
        margin-top: 1.5rem !important;
        display: flex;
        gap: 10px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        body {
            padding: 15px;
        }
        
        #formBox {
            padding: 15px;
        }
        
        .form-row {
            grid-template-columns: 1fr;
            gap: 0.5rem;
            text-align: left;
        }
        
        label {
            text-align: left;
        }
        
        table {
            display: block;
            overflow-x: auto;
        }
        
        .actions {
            flex-direction: column;
            gap: 5px;
        }
        
        .actions .btn {
            width: 100%;
        }
    }

    
    .btn:focus,
    input:focus,
    textarea:focus {
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.25);
    }
</style>
</head>
<body>
    <h1>Quản lý Phim</h1>
     <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại Dashboard
                    </a>

    @if(session('success'))
        <div style="padding:8px;background:#e6ffed;border:1px solid #b7f0c8;margin-bottom:10px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form add / edit --}}
    <div id="formBox">
        <h2 id="formTitle">Thêm Phim</h2>

        <form id="phimForm" action="{{ route('phim.store') }}" method="POST">
            @csrf
            {{-- hidden input holder for method override when editing --}}
            <div id="methodOverride"></div>

            <div class="form-row">
                <label>Tên phim:</label>
                <input type="text" name="TenPhim" id="TenPhim" required maxlength="100">
            </div>

            <div class="form-row">
                <label>Thời lượng (phút):</label>
                <input type="number" name="ThoiLuong" id="ThoiLuong" required min="1">
            </div>

            <div class="form-row">
                <label>Ngày khởi chiếu:</label>
                <input type="date" name="NgayKhoiChieu" id="NgayKhoiChieu" required>
            </div>

            <div class="form-row">
                <label>Nước sản xuất:</label>
                <input type="text" name="NuocSanXuat" id="NuocSanXuat" required maxlength="50">
            </div>

            <div class="form-row">
                <label>Định dạng:</label>
                <input type="text" name="DinhDang" id="DinhDang" required maxlength="20">
            </div>

            <div class="form-row">
                <label>Mô tả:</label>
                <textarea name="MoTa" id="MoTa" rows="3"></textarea>
            </div>

            <div class="form-row">
                <label>Đạo diễn:</label>
                <input type="text" name="DaoDien" id="DaoDien" required maxlength="100">
            </div>

            <div class="form-row">
                <label>Poster:</label>
                <input type="text" name="DuongDanPoster" id="DuongDanPoster">
            </div>

            <div style="margin-top:8px;">
                <button type="submit" id="submitBtn" class="btn btn-primary">Thêm</button>
                <button type="button" id="cancelEditBtn" class="btn btn-secondary" style="display:none;">Hủy</button>
            </div>
        </form>
    </div>

    {{-- Danh sách phim --}}
    <h2>Danh sách Phim</h2>
    <table>
        <thead>
            <tr>
                <th style="width:80px;">Mã Phim</th>
                <th>Tên Phim</th>
                <th style="width:110px;">Thời Lượng</th>
                <th style="width:140px;">Ngày Khởi Chiếu</th>
                <th>Nước Sản Xuất</th>
                <th>Định Dạng</th>
                <th>Mô Tả</th>
                <th>Đạo Diễn</th>
                <th>Poster</th>
                <th style="width:160px;">Hành Động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($phims as $phim)
            <tr data-id="{{ $phim->MaPhim }}"
                data-ten="{{ $phim->TenPhim }}"
                data-thoi="{{ $phim->ThoiLuong }}"
                data-ngay="{{ optional($phim->NgayKhoiChieu)->format('Y-m-d') }}"
                data-nuoc="{{ $phim->NuocSanXuat }}"
                data-dinh="{{ $phim->DinhDang }}"
                data-mota="{{ $phim->MoTa }}"
                data-dao="{{ $phim->DaoDien }}"
                data-poster="{{ $phim->DuongDanPoster }}"
            >
                <td>{{ $phim->MaPhim }}</td>
                <td>{{ $phim->TenPhim }}</td>
                <td>{{ $phim->ThoiLuong }}</td>
                <td>{{ optional($phim->NgayKhoiChieu)->format('Y-m-d') }}</td>
                <td>{{ $phim->NuocSanXuat }}</td>
                <td>{{ $phim->DinhDang }}</td>
                <td>{{ $phim->MoTa }}</td>
                <td>{{ $phim->DaoDien }}</td>
                <td>{{ $phim->DuongDanPoster }}</td>
                <td class="actions">
                    <!-- Sửa: JS sẽ lấy data-* từ tr và fill vào form -->
                    <button type="button" class="btn btn-primary btn-edit" data-id="{{ $phim->MaPhim }}">Sửa</button>

                    <!-- Xóa: form gửi DELETE và server sẽ redirect về admin.phim -->
                    <form action="{{ route('phim.destroy', $phim->MaPhim) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Xác nhận xóa phim?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
            @if($phims->isEmpty())
            <tr><td colspan="10" style="text-align:center">Chưa có phim nào</td></tr>
            @endif
        </tbody>
    </table>

   <script>
    (function(){
        const form = document.getElementById('phimForm');
        const methodOverrideDiv = document.getElementById('methodOverride');
        const submitBtn = document.getElementById('submitBtn');
        const cancelEditBtn = document.getElementById('cancelEditBtn');
        const formTitle = document.getElementById('formTitle');

        // inputs
        const TenPhim = document.getElementById('TenPhim');
        const ThoiLuong = document.getElementById('ThoiLuong');
        const NgayKhoiChieu = document.getElementById('NgayKhoiChieu');
        const NuocSanXuat = document.getElementById('NuocSanXuat');
        const DinhDang = document.getElementById('DinhDang');
        const MoTa = document.getElementById('MoTa');
        const DaoDien = document.getElementById('DaoDien');
        const DuongDanPoster = document.getElementById('DuongDanPoster');

        let editId = null;
        const adminUrl = "{{ route('admin.phim') }}"; // thường /admin/phim

        // Khôi phục form về trạng thái Thêm
        function resetFormToCreate() {
            editId = null;
            form.action = "{{ route('phim.store') }}";
            methodOverrideDiv.innerHTML = ''; // remove _method input
            submitBtn.textContent = 'Thêm';
            cancelEditBtn.style.display = 'none';
            formTitle.textContent = 'Thêm Phim';
            form.reset();
            
            // Thêm code để set min date là ngày hiện tại (không cho chọn ngày quá khứ)
            const today = new Date().toISOString().split('T')[0];
            NgayKhoiChieu.setAttribute('min', today);
        }

        // Điền dữ liệu vào form để sửa
        function fillFormFromRow(tr) {
            editId = tr.getAttribute('data-id');
            TenPhim.value = tr.getAttribute('data-ten') || '';
            ThoiLuong.value = tr.getAttribute('data-thoi') || '';
            NgayKhoiChieu.value = tr.getAttribute('data-ngay') || '';
            NuocSanXuat.value = tr.getAttribute('data-nuoc') || '';
            DinhDang.value = tr.getAttribute('data-dinh') || '';
            MoTa.value = tr.getAttribute('data-mota') || '';
            DaoDien.value = tr.getAttribute('data-dao') || '';
            DuongDanPoster.value = tr.getAttribute('data-poster') || '';

            // set form action to update route
            // route('phim.update', id) => /phim/{id}
            form.action = "{{ route('phim.update', ':id') }}".replace(':id', editId);

            // add method override _method=PUT if not exists
            methodOverrideDiv.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            submitBtn.textContent = 'Cập nhật';
            cancelEditBtn.style.display = 'inline-block';
            formTitle.textContent = 'Sửa Phim (ID: ' + editId + ')';
            
            // Xóa restriction min date khi edit (cho phép chọn ngày quá khứ khi sửa)
            NgayKhoiChieu.removeAttribute('min');
        }

        // Attach click events to Edit buttons
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function(e){
                const row = this.closest('tr');
                fillFormFromRow(row);
                // scroll to form
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });

        // Cancel edit
        cancelEditBtn.addEventListener('click', function(){
            resetFormToCreate();
        });

        // On page load ensure form is in create mode
        resetFormToCreate();
    })();
</script>
</body>
</html>
