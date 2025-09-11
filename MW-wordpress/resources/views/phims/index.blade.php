<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quản lý Phim</title>
    <style>
        .pagination { margin-top: 20px; }
        .pagination li { display: inline-block; margin-right: 5px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
    </style>
</head>
<body>
<h1>Quản lý Phim</h1>
@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif
    <h3>Thêm phim mới</h3>
        <form method="POST" action="{{ route('phim.store') }}">
        @csrf
        <input name="ten_phim" placeholder="Tên phim" required>
        <input name="thoi_luong" placeholder="Thời lượng">
        <button type="submit">Thêm</button>
    </form>
    <table>
<thead>
        <tr>
            <th>Mã phim</th>
            <th>Tên phim</th>
            <th>Thời lượng</th>
            <th>Ngày khởi chiếu</th>
            <th>Nước SX</th>
            <th>Định dạng</th>
            <th>Mô tả</th>
            <th>Hành động</th>
        </tr>
</thead>
<tbody>
@foreach($items as $p)
<tr>
<td>{{ $p->ma_phim }}</td>
<form method="POST" action="{{ route('phim.update', $p->ma_phim) }}">
@csrf
@method('PUT')
<td><input name="ten_phim" value="{{ $p->ten_phim }}"></td>
<td><input name="thoi_luong" value="{{ $p->thoi_luong }}"></td>
<td><input name="ngay_khoi_chieu" value="{{ $p->ngay_khoi_chieu }}"></td>
<td><input name="nuoc_san_xuat" value="{{ $p->nuoc_san_xuat }}"></td>
<td><input name="dinh_dang" value="{{ $p->dinh_dang }}"></td>
<td><textarea name="mo_ta">{{ $p->mo_ta }}</textarea></td>
<td>
<button type="submit">Cập nhật</button>
</form>
<form method="POST" action="{{ route('phim.destroy', $p->ma_phim) }}" style="display:inline">
@csrf
@method('DELETE')
<button type="submit" onclick="return confirm('Xóa phim này?')">Xóa</button>
</form>
</td>
</tr>
@endforeach
</tbody>
</table>


<div class="pagination">
{{ $items->links() }}
</div>


</body>
</html>