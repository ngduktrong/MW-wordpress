<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Rạp Chiếu Phim</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f5f5f5;
        color: #333;
        line-height: 1.6;
    }
    a {
        text-decoration: none;
    }
    header {
        background: #1a1a1a;
        color: white;
        padding: 1.2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-bottom: 3px solid #e0e0e0;
    }
    
    header h1 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
    }
    
    header small {
        color: #ccc;
        font-size: 0.9rem;
    }
    
    .container {
        display: flex;
        min-height: calc(100vh - 80px);
    }
    
    nav {
        background: #ffffff;
        width: 220px;
        padding: 1.5rem 1rem;
        border-right: 1px solid #e0e0e0;
        box-shadow: 2px 0 5px rgba(0,0,0,0.05);
    }
    
    nav a {
        display: block;
        padding: 12px 15px;
        text-decoration: none;
        color: #333;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
        border-radius: 4px;
        margin-bottom: 5px;
        font-weight: 500;
    }
    
    nav a:hover {
        background: #f0f0f0;
        color: #000;
        transform: translateX(3px);
    }
    
    main {
        flex: 1;
        padding: 2rem;
        background-color: #f9f9f9;
    }
    
    .welcome-section {
        margin-bottom: 2.5rem;
        padding: 1.5rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-left: 4px solid #1a1a1a;
    }
    
    .welcome-section h2 {
        margin-top: 0;
        color: #1a1a1a;
        font-weight: 600;
    }
    
    .welcome-section p {
        color: #666;
        margin-bottom: 0;
    }
    
    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }
    
    .card {
        background: white;
        padding: 1.8rem 1.2rem;
        border-radius: 8px;
        text-align: center;
        border: 1px solid #e0e0e0;
        box-shadow: 0 3px 6px rgba(0,0,0,0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .card h3 {
        font-size: 1.8rem;
        margin: 0 0 0.8rem 0;
        color: #1a1a1a;
        font-weight: 700;
    }
    
    .card p {
        margin: 0;
        color: #666;
        font-size: 0.95rem;
        font-weight: 500;
    }
    
    .chart-container {
        background: white;
        padding: 1.8rem;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        box-shadow: 0 3px 6px rgba(0,0,0,0.08);
    }
    
    a[href*="logout"] {
        background: #333;
        padding: 8px 16px;
        border-radius: 4px;
        transition: background 0.3s ease;
    }
    
    a[href*="logout"]:hover {
        background: #555;
    }
</style>
</head>
<body>
<header>
    <div>
        <h1>Hệ Thống Quản Lý Rạp Chiếu Phim</h1>
        <small>TML Cinema</small>
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
    <a href="{{ route('admin.nhanvien.index') }}">Nhân Viên</a>

    <a href="{{ route('admin.khachhang.index') }}">Khách Hàng</a>
    <a href="{{ route('admin.phim') }}">Phim</a>
    <a href="{{ route('admin.phongchieu.index') }}">Phòng Chiếu</a>
    <a href="{{ route('admin.suatchieu.index') }}">Suất Chiếu</a>
    <a href="{{ route('ghe.index') }}">Ghế</a>
    <a href="{{ route('admin.ve.index') }}">Vé</a>
    <a href="{{ route('admin.hoadon.index') }}">Hóa Đơn</a>
    <a href="{{ route('admin.kiemtra.index') }}">Thông báo</a>
    </nav>
    <main>
        <div class="welcome-section">
            <h2>Xin chào Admin: {{ Auth::user()->TenDangNhap ?? 'Quản trị viên' }}</h2>
            <p>Chào mừng bạn đến với hệ thống quản lý rạp chiếu phim.</p>
        </div>

        <div class="card-grid">
            
           <div class="card-grid">
    <div class="card">
        <h3>{{ number_format($tongVeDaThanhToan) }}</h3>
        <p>Tổng vé đã thanh toán</p>
    </div>
    
    <div class="card">
        <h3>{{ number_format($tongDoanhThu) }}đ</h3>
        <p>Tổng doanh thu</p>
    </div>
    
    <div class="card">
        <h3>{{ number_format($tongVeHomNay) }}</h3>
        <p>Vé hôm nay</p>
    </div>
    
    <div class="card">
        <h3>{{ number_format($tongDoanhThuHomNay) }}đ</h3>
        <p>Doanh thu hôm nay</p>
    </div>
</div>
        
    </main>
</div>
</body>
</html>