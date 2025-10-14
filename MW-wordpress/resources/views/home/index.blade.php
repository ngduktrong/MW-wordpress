@extends('layouts.app')

@section('content')
<style>
    body {
    position: relative;
    background-image: url('/img/home-wallpaper.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed; /* ✅ để hình nền không bị cuộn */
    color: aliceblue;
}

/* ✅ lớp phủ màu đen tự động bao phủ toàn bộ trang */
body::after {
    content: '';
    position: fixed; /* cố định phủ toàn màn hình */
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    z-index: 0;
    pointer-events: none;
}

/* ✅ nội dung nổi phía trên layer đen */
body > * {
    position: relative;
    z-index: 1;
}

    .content-home {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
    }

    /* ✅ Dùng chung cho cả phim đang chiếu và sắp chiếu */
    .film-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 50px;
        color: aliceblue;
    }

    .film-list {
        list-style: none;
        max-width: 1200px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        padding: 0;
        justify-items: start;
    }

    .film-item {
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
        background-color: rgba(255, 255, 255, 0.8);
    }

    .film-poster {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .film-infor-detail {
        text-align: left;
        padding-left: 30px;
    }

    .film-infor-bookBtn {
        display: flex;
        justify-content: space-around;
        align-items: center;
        margin-top: 10px;
    }

    .love-icon {
        display: flex;
        align-items: center;
        color: rgb(160, 160, 160);
        cursor: pointer;
        transition: color 0.3s ease;
        font-size: 26px;
        border-radius: 60px;
    }

    .love-icon:hover {
        color: rgb(68, 67, 67);
    }

    .love-icon.active {
        color: red;
    }

    .btnbookTk {
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 40px;
        padding: 10px 20px;
        background-color: #ff6600;
        color: white;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btnbookTk:hover {
        background-color: #cc5200;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.love-icon').forEach(function (icon) {
            icon.addEventListener('click', function () {
                this.classList.toggle('active');
            });
        });
    });
</script>

@include('layouts.nav')

<div class="content-home">

    {{-- ✅ Phim đang chiếu --}}
    <div class="film-section">
        <h2>Phim đang chiếu</h2>
        <ul class="film-list">
            @forelse($phimDangChieu as $phim)
                <li class="film-item">
                    <a href="{{ route('home.show', $phim->MaPhim) }}">
                        <img src="/img/{{ $phim->DuongDanPoster }}" alt="" class="film-poster">
                        <div class="film-infor-detail">
                            Tên Phim: {{ $phim->TenPhim }} <br>
                            Khởi chiếu: {{ $phim->NgayKhoiChieu }} <br>
                            Thời Lượng: {{ $phim->ThoiLuong }} <br>
                            Quốc gia: {{ $phim->NuocSanXuat }}
                        </div>
                    </a>

                    <form action="{{ route('home.show', [$phim->MaPhim]) }}" method="GET">
                        <div class="film-infor-bookBtn">
                            <i class="fa-solid fa-heart love-icon"></i>
                            <button class="btnbookTk btn-shadow">Đặt Vé</button>
                        </div>
                    </form>
                </li>
            @empty
                <li>Hiện tại chưa có phim nào đang chiếu.</li>
            @endforelse
        </ul>
    </div>

    {{-- ✅ Phim sắp chiếu --}}
    <div class="film-section">
        <h2>Phim sắp chiếu</h2>
        <ul class="film-list">
            @forelse($phimSapChieu as $phim)
                <li class="film-item">
                    <a href="{{ route('home.show', $phim->MaPhim) }}">
                        <img src="/img/{{ $phim->DuongDanPoster }}" alt="" class="film-poster">
                        <div class="film-infor-detail">
                            Tên Phim: {{ $phim->TenPhim }} <br>
                            Khởi chiếu: {{ $phim->NgayKhoiChieu }} <br>
                            Thời Lượng: {{ $phim->ThoiLuong }} <br>
                            Quốc gia: {{ $phim->NuocSanXuat }}
                        </div>
                    </a>
                     <form action="{{ route('home.show', [$phim->MaPhim]) }}" method="GET">
                        <div class="film-infor-bookBtn">
                            <i class="fa-solid fa-heart love-icon"></i>
                            <button class="btnbookTk btn-shadow">Đặt Vé</button>
                        </div>
                    </form>
                </li>
            @empty
                <li>Không có phim sắp chiếu.</li>
            @endforelse
        </ul>
    </div>

</div>
@endsection
