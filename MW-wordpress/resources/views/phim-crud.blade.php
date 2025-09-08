<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>CRUD Phim 1 Trang</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h1>Quản lý Phim (1 trang)</h1>

    <form id="phimForm">
        <input type="hidden" id="MaPhim">
        <input type="text" id="TenPhim" placeholder="Tên phim" required>
        <input type="number" id="ThoiLuong" placeholder="Thời lượng (phút)">
        <button type="submit">Lưu</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th><th>Tên phim</th><th>Thời lượng</th><th>Hành động</th>
            </tr>
        </thead>
        <tbody id="phimTable"></tbody>
    </table>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

async function loadPhims() {
    let res = await fetch('/api/phims');
    let json = await res.json();
    let tbody = document.getElementById('phimTable');
    tbody.innerHTML = '';
    if (json.data) {
        json.data.forEach(p => {
            tbody.innerHTML += `
                <tr>
                    <td>${p.MaPhim}</td>
                    <td>${p.TenPhim}</td>
                    <td>${p.ThoiLuong ?? ''}</td>
                    <td>
                        <button onclick="editPhim(${p.MaPhim}, '${p.TenPhim}', ${p.ThoiLuong ?? 0})">Sửa</button>
                        <button onclick="deletePhim(${p.MaPhim})">Xóa</button>
                    </td>
                </tr>`;
        });
    }
}

document.getElementById('phimForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    let id = document.getElementById('MaPhim').value;
    let data = {
        TenPhim: document.getElementById('TenPhim').value,
        ThoiLuong: document.getElementById('ThoiLuong').value
    };

    let url = id ? `/api/phims/${id}` : '/api/phims';
    let method = id ? 'PUT' : 'POST';

    await fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify(data)
    });

    e.target.reset();
    document.getElementById('MaPhim').value = '';
    loadPhims();
});

function editPhim(id, ten, thoiluong) {
    document.getElementById('MaPhim').value = id;
    document.getElementById('TenPhim').value = ten;
    document.getElementById('ThoiLuong').value = thoiluong;
}

async function deletePhim(id) {
    if (!confirm('Xóa phim này?')) return;
    await fetch(`/api/phims/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    });
    loadPhims();
}

loadPhims();
</script>
</body>
</html>
