<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Rạp Chiếu Phim</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background: #333;
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .container {
            display: flex;
            min-height: calc(100vh - 80px);
        }
        nav {
            background: #f4f4f4;
            width: 200px;
            padding: 1rem;
        }
        nav a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #333;
            border-bottom: 1px solid #ddd;
        }
        nav a:hover {
            background: #ddd;
        }
        main {
            flex: 1;
            padding: 2rem;
        }
        .welcome-section {
            margin-bottom: 2rem;
        }
        .card-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .card {
            background: #f9f9f9;
            padding: 1rem;
            border-radius: 5px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .chart-container {
            background: #f9f9f9;
            padding: 1rem;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
<header>
    <div>
        <h1>Hệ Thống Quản Lý Rạp Chiếu Phim</h1>
        <small>T&M Cinema</small>
    </div>
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: white;">
        Đăng xuất
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</header>
<div class="container">
    <nav>
        <a href="{{ route('admin.taikhoan.index') }}">Tài Khoản</a>
    <a href="{{ route('admin.nguoidung.index') }}">Người Dùng</a>
    <a href="/admin/nhanvien">Nhân Viên</a>
    <a href="{{ route('admin.khachhang.index') }}">Khách Hàng</a>
    <a href="{{ route('admin.phim') }}">Phim</a>
    <a href="{{ route('admin.phongchieu.index') }}">Phòng Chiếu</a>
    <a href="{{ route('admin.suatchieu.index') }}">Suất Chiếu</a>
    <a href="{{ route('ghe.index') }}">Ghế</a>
    <a href="#">Vé</a>
    <a href="#">Hóa Đơn</a>
    <a href="#">Thông báo</a>
    </nav>
    <main>
        <div class="welcome-section">
            <h2>Xin chào Admin: {{ Auth::user()->TenDangNhap ?? 'Quản trị viên' }}</h2>
            <p>Chào mừng bạn đến với hệ thống quản lý rạp chiếu phim.</p>
        </div>

        <div class="card-grid">
            <div class="card">
                <h3>42</h3>
                <p>Phim đang chiếu</p>
            </div>
            <div class="card">
                <h3>1,248</h3>
                <p>Vé đã bán hôm nay</p>
            </div>
            <div class="card">
                <h3>524</h3>
                <p>Khách hàng trực tuyến</p>
            </div>
            <div class="card">
                <h3>18.5tr</h3>
                <p>Doanh thu hôm nay</p>
            </div>
        </div>

        <div class="chart-container">
            <h3>Thống Kê Doanh Thu</h3>
            <p>Biểu đồ doanh thu sẽ được hiển thị tại đây.</p>
        </div>
    </main>
</div>
</body>
</html>