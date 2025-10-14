<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Thông báo - Vé sắp chiếu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-image: url('/img/home-wallpaper.jpg');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .container {
      max-width: 800px;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      margin-top: 2rem;
      margin-bottom: 2rem;
      padding: 2rem;
      backdrop-filter: blur(5px);
    }

    h3 {
      color: #2c3e50;
      font-weight: 700;
      border-bottom: 3px solid #3498db;
      padding-bottom: 10px;
      margin-bottom: 1.5rem;
    }

    .alert-warning {
      border-radius: 10px;
      background-color: rgba(255, 243, 205, 0.9);
      border: 2px solid #ffeaa7;
      color: #856404;
      font-size: 1.1rem;
      text-align: center;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .list-group-item {
      border: none;
      border-radius: 10px !important;
      margin-bottom: 15px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
      border-left: 4px solid #3498db;
      background: rgba(255, 255, 255, 0.9);
    }

    .list-group-item:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
      background: white;
    }

    .list-group-item strong {
      color: #2c3e50;
      font-size: 1.2rem;
    }

    .text-end small {
      color: #e74c3c;
      font-weight: 500;
    }

    .btn-secondary {
      background: linear-gradient(135deg, #95a5a6, #7f8c8d);
      border: none;
      border-radius: 25px;
      padding: 8px 25px;
      transition: all 0.3s ease;
    }

    .btn-secondary:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>
<body>
  <div class="container py-4">
    
    @if($isAlertMode)
      <div class="alert alert-warning">
        <h4>⚠️ Thông báo quan trọng!</h4>
        <p class="mb-0"><strong>Bạn có vé sắp chiếu trong vòng 1 giờ tới!</strong><br>
        Hãy đến rạp đúng giờ để không bỏ lỡ suất chiếu.</p>
      </div>
      <h3 class="mb-3">Vé sắp chiếu của bạn (Trong 1 giờ tới)</h3>
    @else
      <h3 class="mb-3">Vé sắp chiếu của bạn</h3>
    @endif

    <p class="text-muted mb-4">
      🎬 Các vé sắp chiếu của bạn, nhớ lưu ý đến rạp đúng giờ để trải nghiệm phim trọn vẹn nhé!
    </p>

    @if($ves->isEmpty())
      @if($isAlertMode)
        <div class="alert alert-info">Hiện không có vé sắp chiếu trong vòng 1 giờ tới.</div>
      @else
        <div class="alert alert-info">Hiện không có vé sắp chiếu.</div>
      @endif
    @else
      <div class="list-group">
        @foreach($ves as $v)
          <div class="list-group-item">
            <div class="d-flex w-100 justify-content-between">
              <div>
                <strong>{{ $v->movie ?? 'Tên phim' }}</strong><br>
                <small>Mã vé: {{ $v->code ?? '-' }}</small>
              </div>
              <div class="text-end small">
                @if($v->showtime)
                  {{ \Carbon\Carbon::parse($v->showtime)->format('d/m/Y H:i') }}
                @else
                  -
                @endif
                <div>Phòng: {{ $v->room ?? '-' }} • Ghế: {{ $v->seat ?? '-' }}</div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif

    <div class="mt-3">
      <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">Quay lại</a>
      @if($isAlertMode)
        <a href="{{ route('thongbao') }}" class="btn btn-primary btn-sm">Xem tất cả vé sắp chiếu</a>
      @endif
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>