<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Rạp Chiếu Phim</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
}

body{
    background:#0f172a;
    color:#e5e7eb;
}

a{
    text-decoration:none;
}

header{
    background:linear-gradient(90deg,#020617,#111827);
    padding:18px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px solid #1f2937;
    box-shadow:0 4px 15px rgba(0,0,0,0.5);
}

header h1{
    font-size:1.5rem;
    font-weight:600;
    color:white;
}

header small{
    color:#9ca3af;
}

header a{
    background:#ef4444;
    padding:8px 18px;
    border-radius:6px;
    color:white;
    font-weight:500;
    transition:.3s;
}

header a:hover{
    background:#dc2626;
}

.container{
    display:flex;
    min-height:calc(100vh - 70px);
}

nav{
    width:240px;
    background:#020617;
    padding:25px 15px;
    border-right:1px solid #1f2937;
}

nav a{
    display:block;
    padding:12px 14px;
    margin-bottom:6px;
    border-radius:6px;
    color:#cbd5f5;
    font-size:.95rem;
    transition:.25s;
}

nav a:hover{
    background:#1e293b;
    color:white;
    transform:translateX(4px);
}

main{
    flex:1;
    padding:35px;
    background:#0f172a;
}

.welcome-section{
    margin-bottom:30px;
    padding:22px;
    border-radius:10px;
    background:linear-gradient(135deg,#1e293b,#020617);
    border:1px solid #1f2937;
    box-shadow:0 8px 25px rgba(0,0,0,0.4);
}

.welcome-section h2{
    margin-bottom:6px;
    color:white;
    font-weight:600;
}

.welcome-section p{
    color:#94a3b8;
}

.card-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

.card{
    background:linear-gradient(145deg,#020617,#1e293b);
    border-radius:12px;
    padding:28px 20px;
    text-align:center;
    border:1px solid #1f2937;
    transition:.3s;
    position:relative;
    overflow:hidden;
}

.card::before{
    content:"";
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:4px;
    background:linear-gradient(90deg,#06b6d4,#3b82f6);
}

.card:hover{
    transform:translateY(-6px);
    box-shadow:0 10px 30px rgba(0,0,0,0.6);
}

.card h3{
    font-size:2rem;
    margin-bottom:8px;
    color:#22c55e;
    font-weight:700;
}

.card p{
    color:#94a3b8;
    font-size:.9rem;
}

.chart-container{
    margin-top:30px;
    background:#020617;
    padding:25px;
    border-radius:10px;
    border:1px solid #1f2937;
}

@media(max-width:900px){

    nav{
        width:200px;
    }

}

@media(max-width:700px){

    .container{
        flex-direction:column;
    }

    nav{
        width:100%;
        display:flex;
        flex-wrap:wrap;
        gap:6px;
    }

    nav a{
        flex:1 1 45%;
        text-align:center;
    }

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