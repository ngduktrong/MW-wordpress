<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Thông báo - Vé sắp chiếu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container py-4">
    <h3 class="mb-3">Vé sắp chiếu của bạn</h3>

    <p class="text-muted mb-4">
      🎬 Các vé sắp chiếu của bạn, nhớ lưu ý đến rạp đúng giờ để trải nghiệm phim trọn vẹn nhé!
    </p>

    @if($ves->isEmpty())
      <div class="alert alert-info">Hiện không có vé sắp chiếu.</div>
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
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
