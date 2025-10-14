@extends('layouts.app')

@section('content')
@include('layouts.nav')
<style>
     body {
        background-image: url('/img/home-wallpaper.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        height: 100vh;
    }

    body::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.6);
        z-index: 0;
        pointer-events: none;
        height: auto;
        height: min(1100px);
    }

    .container>* {
        position: relative;
        z-index: 2;
    }
    .hoadon-infor {
        color: rgb(0, 0, 0);
        padding: 20px;
        width: 800px;
        padding: 30px;
        display: flex;
        flex-direction: column;
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 15px;
    }
</style>
<div class="container">

<div class="hoadon-infor">
    <h2>Chi tiết vé</h2>
<p>Khách hàng: {{ $hoaDon->khachHang->HoTen ?? 'Khách lẻ' }}</p>
<p>Hóa đơn: {{ $hoaDon->MaHoaDon }}</p>
<p>Trạng thái hóa đơn: {{ $hoaDon->TrangThai }}</p>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Mã vé</th>
            <th>Phim</th>
            <th>Suất chiếu</th>
            <th>Phòng</th>
            <th>Ghế</th>
            <th>Giá vé</th>
            <th>Trạng thái</th>
            <th>Ngày đặt</th>
        </tr>
    </thead>
    <tbody>
       @foreach($hoaDon->ves as $ve)  <!-- Chú ý là "ves", không phải "ve" -->
<tr>
    <td>{{ $ve->MaVe }}</td>
    <td>{{ $ve->suatChieu->phim->TenPhim }}</td>
    <td>{{ $ve->suatChieu->NgayGioChieu }}</td>
    <td>{{ $ve->suatChieu->phongChieu->TenPhong }}</td>
    <td>{{ $ve->SoGhe }}</td>
    <td>{{ number_format($ve->GiaVe, 0, ',', '.') }} VND</td>
    <td>{{ $ve->TrangThai }}</td>
    <td>{{ $ve->NgayDat }}</td>
</tr>
@endforeach

    </tbody>
</table>

<p><b>Tổng tiền:</b> {{ number_format($hoaDon->TongTien, 0, ',', '.') }} VND</p>


<a href="{{ route('home') }}">Quay về trang chủ</a>
</div>
</div>
@endsection
