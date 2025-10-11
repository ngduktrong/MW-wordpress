@extends('layouts.app')

@section('content')
<div class="ticket-container">
    <h2>🎟️ Vé của tôi</h2>

    @if($ves->isEmpty())
        <p>Bạn chưa đặt vé nào.</p>
    @else
        <table class="ticket-table">
            <thead>
                <tr>
                    <th>Mã vé</th>
                    <th>Tên phim</th>
                    <th>Phòng chiếu</th>
                    <th>Số ghế</th>
                    <th>Giá vé</th>
                    <th>Ngày chiếu</th>
                    <th>Ngày đặt</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ves as $ve)
                <tr>
                    <td>{{ $ve->MaVe }}</td>
                    <td>{{ $ve->suatChieu->phim->TenPhim ?? 'N/A' }}</td>
                    <td>{{ $ve->phongChieu->TenPhong ?? '' }}</td>
                    <td>{{ $ve->SoGhe }}</td>
                    <td>{{ number_format($ve->GiaVe, 0, ',', '.') }} đ</td>
                    <td>
    {{ \Carbon\Carbon::parse($ve->suatChieu->NgayGioChieu)->format('d/m/Y H:i') }}
</td>
                    <td>{{ $ve->NgayDat ? $ve->NgayDat->format('d/m/Y H:i') : 'Chưa đặt' }}</td>
                    <td>
                        <span class="status {{ strtolower($ve->TrangThai) }}">
                            {{ ucfirst($ve->TrangThai) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('home') }}" class="back-btn">← Quay lại trang cá nhân</a>
</div>
@endsection

<style>
.ticket-container {
    max-width: 900px;
    margin: 40px auto;
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.ticket-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.ticket-table th, .ticket-table td {
    border: 1px solid #ddd;
    padding: 10px 12px;
    text-align: center;
}

.ticket-table th {
    background-color: #007bff;
    color: white;
}

.status.paid {
    color: green;
    font-weight: bold;
}
.status.pending {
    color: orange;
    font-weight: bold;
}
.status['đã đặt'] {
    color: #555;
}

.back-btn {
    display: inline-block;
    margin-top: 20px;
    text-decoration: none;
    color: #007bff;
}
.back-btn:hover {
    text-decoration: underline;
}
</style>
