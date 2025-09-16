<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Rạp Chiếu Phim</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1f2937;
            --secondary: #374151;
            --accent: #3b82f6;
            --accent-hover: #2563eb;
            --bg: #f9fafb;
            --text: #111827;
            --text-light: #6b7280;
            --card-bg: white;
            --border-radius: 12px;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }
        
        /* ===== HEADER STYLES ===== */
        header {
            background: linear-gradient(135deg, var(--primary) 0%, #111827 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow);
            z-index: 100;
            flex-shrink: 0;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo {
            font-size: 1.8rem;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        header h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .logout-btn {
            color: white;
            text-decoration: none;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 6px;
            background-color: var(--accent);
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .logout-btn:hover {
            background-color: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        /* ===== MAIN CONTAINER STYLES ===== */
        .container {
            display: flex;
            flex: 1;
            overflow: hidden;
        }
        
        /* ===== SIDEBAR STYLES ===== */
        nav {
            background: linear-gradient(180deg, var(--secondary) 0%, #283447 100%);
            color: white;
            width: 240px;
            padding: 1.5rem 0;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            overflow-y: auto;
            flex-shrink: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 90;
        }
        
        nav a {
            color: white;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            margin: 0 0.5rem;
            border-left: 4px solid transparent;
            position: relative;
            overflow: hidden;
        }
        
        nav a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: var(--transition);
        }
        
        nav a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: var(--accent);
        }
        
        nav a:hover::before {
            left: 100%;
        }
        
        nav a i {
            width: 24px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        /* ===== MAIN CONTENT STYLES ===== */
        main {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
            background-color: var(--bg);
            display: flex;
            flex-direction: column;
        }
        
        .welcome-section {
            background: linear-gradient(135deg, #ffffff 0%, #f3f4f6 100%);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }
        
        .welcome-section::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: var(--accent);
            border-radius: 50%;
            opacity: 0.1;
        }
        
        .welcome-section h2 {
            margin-top: 0;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }
        
        .welcome-section p {
            color: var(--text-light);
            max-width: 800px;
            line-height: 1.7;
            font-size: 1.05rem;
        }
        
        /* ===== CARD GRID STYLES ===== */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .card {
            background: linear-gradient(135deg, var(--card-bg) 0%, #f8fafc 100%);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            text-align: center;
            transition: var(--transition);
            border: 1px solid #e5e7eb;
            position: relative;
            overflow: hidden;
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--accent);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .card i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--accent);
            display: block;
        }
        
        .card h2 {
            margin: 0;
            font-size: 2rem;
            color: var(--accent);
        }
        
        .card p {
            color: var(--text-light);
            margin: 0.5rem 0 0;
            font-size: 0.9rem;
        }
        
        /* ===== CHART SECTION ===== */
        .chart-container {
            background: linear-gradient(135deg, var(--card-bg) 0%, #f8fafc 100%);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow);
            margin-top: auto;
            flex: 1;
            display: flex;
            flex-direction: column;
            border: 1px solid #e5e7eb;
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .chart-header h3 {
            font-size: 1.3rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .chart-actions {
            display: flex;
            gap: 10px;
        }
        
        .chart-btn {
            background-color: var(--accent);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .chart-btn:hover {
            background-color: var(--accent-hover);
        }
        
        .chart-content {
            background-color: rgba(59, 130, 246, 0.05);
            border-radius: 8px;
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 1px dashed #cbd5e1;
        }
        
        .chart-placeholder {
            text-align: center;
            color: var(--text-light);
            max-width: 500px;
        }
        
        .chart-placeholder i {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            color: var(--accent);
            opacity: 0.3;
        }
        
        .chart-placeholder h4 {
            font-size: 1.4rem;
            margin-bottom: 0.5rem;
            color: var(--primary);
        }
        
        .chart-placeholder p {
            line-height: 1.6;
        }
        
        /* ===== RESPONSIVE STYLES ===== */
        @media (max-width: 1024px) {
            .card-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }
        
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            nav {
                width: 100%;
                flex-direction: row;
                overflow-x: auto;
                padding: 1rem 0;
                gap: 0;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }
            
            nav a {
                padding: 0.5rem 1rem;
                border-left: none;
                border-bottom: 4px solid transparent;
                margin: 0 0.2rem;
                white-space: nowrap;
            }
            
            nav a:hover {
                border-left: none;
                border-bottom-color: var(--accent);
            }
            
            main {
                padding: 1.5rem;
            }
            
            .welcome-section {
                padding: 1.5rem;
            }
            
            .card-grid {
                grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                gap: 1rem;
            }
            
            .chart-container {
                padding: 1.5rem;
            }
            
            .chart-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .chart-actions {
                width: 100%;
                justify-content: center;
            }
        }
        
        @media (max-width: 480px) {
            header {
                flex-direction: column;
                gap: 15px;
                padding: 1rem;
            }
            
            .header-left {
                flex-direction: column;
                text-align: center;
            }
            
            .logout-btn {
                width: 100%;
                justify-content: center;
            }
            
            .card-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<header>
    <div class="header-left">
        <div class="logo">🎬</div>
        <div>
            <h1>Hệ Thống Quản Lý Rạp Chiếu Phim</h1>
            <small>T&M Cinema - Your Movie Experience</small>
        </div>
    </div>

    <a href="{{ route('logout') }}" class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i> Đăng xuất
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</header>
<div class="container">
    <nav>
        <a href="#"><i class="fas fa-user-circle"></i> Tài Khoản</a>
        <a href="#"><i class="fas fa-users"></i> Người Dùng</a>
        <a href="#"><i class="fas fa-user-tie"></i> Nhân Viên</a>
        <a href="#"><i class="fas fa-user-friends"></i> Khách Hàng</a>
        <a href="{{ route('admin.phim') }}"><i class="fas fa-video"></i> Phim</a>
        <a href="#"><i class="fas fa-theater-masks"></i> Phòng Chiếu</a>
        <a href="#"><i class="fas fa-calendar-alt"></i> Suất Chiếu</a>
        <a href="#"><i class="fas fa-ticket-alt"></i> Vé</a>
        <a href="#"><i class="fas fa-receipt"></i> Hóa Đơn</a>
        <a href="#"><i class="fas fa-chair"></i> Ghế </a>
        <a href="#"><i class="fas fa-bell"></i> Thông báo đến user</a>
    </nav>
    <main>
        <div class="welcome-section">
            <h2>
                <i class="fas fa-hand-sparkles"></i>
                Xin chào Admin: <span>{{ Auth::user()->name ?? 'Quản trị viên' }}</span>
            </h2>

            <p>
                Mã người dùng của bạn: <strong>{{ Auth::id() ?? 'N/A' }}</strong>
            </p>

            <p>
                <strong>Phát triển bởi</strong>: Nguyễn Đức Trọng |
                <strong>Hotline</strong>: 
                <a href="tel:0983241301" style="color: #007BFF; text-decoration: none;">0983 241 301</a> |
                <a href="https://www.facebook.com/Duktrong/" target="_blank" style="color: #4267B2; text-decoration: none;">
                    <i class="fab fa-facebook-square"></i> Facebook
                </a> |
                <a href="https://www.youtube.com/watch?v=GOU-oDzszp0&t=280s" target="_blank" style="color: #FF0000; text-decoration: none;">
                    <i class="fab fa-youtube"></i> YouTube
                </a>
            </p>

            <p>
                Chào mừng bạn đến với <strong>hệ thống quản lý rạp chiếu phim</strong>. Tại đây, bạn có thể quản lý toàn bộ hoạt động như:
                phim, suất chiếu, phòng chiếu, doanh thu và nhân viên một cách dễ dàng và hiệu quả.
            </p>
        </div>

        <div class="card-grid">
            <div class="card">
                <i class="fas fa-film"></i>
                <h2>42</h2>
                <p>Phim đang chiếu</p>
            </div>
            <div class="card">
                <i class="fas fa-ticket-alt"></i>
                <h2>1,248</h2>
                <p>Vé đã bán hôm nay</p>
            </div>
            <div class="card">
                <i class="fas fa-users"></i>
                <h2>524</h2>
                <p>Khách hàng trực tuyến</p>
            </div>
            <div class="card">
                <i class="fas fa-dollar-sign"></i>
                <h2>18.5tr</h2>
                <p>Doanh thu hôm nay</p>
            </div>
        </div>

        <div class="chart-container">
            <div class="chart-header">
                <h3><i class="fas fa-chart-line"></i> Thống Kê Doanh Thu</h3>
                <div class="chart-actions">
                    <button class="chart-btn"><i class="fas fa-download"></i> Xuất Excel</button>
                    <button class="chart-btn"><i class="fas fa-filter"></i> Lọc</button>
                </div>
            </div>
            <div class="chart-content">
                <div class="chart-placeholder">
                    <i class="fas fa-chart-bar"></i>
                    <h4>Biểu đồ doanh thu</h4>
                    <p>Dữ liệu thống kê doanh thu sẽ được hiển thị trực quan tại đây khi có dữ liệu</p>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>