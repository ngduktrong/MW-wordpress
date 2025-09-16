<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quản lý Phim</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; padding: 16px; }
        table { border-collapse: collapse; width: 100%; margin-top: 16px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; vertical-align: middle; }
        th { background: #f4f4f4; }
        input[type="text"], input[type="number"], input[type="date"], textarea { width: 100%; padding: 6px; box-sizing: border-box; }
        .form-row { display: grid; grid-template-columns: 160px 1fr; gap: 8px; margin-bottom: 8px; align-items: center; }
        .actions { display:flex; gap:8px; align-items:center; }
        .btn { padding: 6px 10px; cursor:pointer; }
        .btn-danger { background:#e74c3c; color:white; border:none; }
        .btn-primary { background:#3498db; color:white; border:none; }
        .btn-secondary { background:#95a5a6; color:white; border:none; }
    </style>
</head>
<body>
    <h1>Quản lý Phim</h1>

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
                form.action = '/phim/' + editId;

                // add method override _method=PUT if not exists
                methodOverrideDiv.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                submitBtn.textContent = 'Cập nhật';
                cancelEditBtn.style.display = 'inline-block';
                formTitle.textContent = 'Sửa Phim (ID: ' + editId + ')';
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
