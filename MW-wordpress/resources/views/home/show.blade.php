@extends('layouts.app')

@section('content')

    <style>
        

        body {
            background-image: url('/img/{{ $phim->DuongDanPoster }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
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

        .container-film>* {
            position: relative;
            z-index: 1;
        }

        .film-describe {
            color: white;
            padding: 20px;
            max-width: 800px;
            padding-top: 200px;
            /* margin: 0 auto; */
            text-align: center;
        }

        .film-type-label {
            background-color: rgba(255, 255, 255, 0.8);
            color: black;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 24px;
            margin-left: 10px;
            height: 40px;
            margin-left: 20px;
        }

        .film-name {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .film-infor>span {
            background-color: rgba(255, 255, 255, 0.8);
            color: black;
            padding: 5px 10px;
            border-radius: 15px;
            margin: 0 10px;
        }

        .film-select-schedule {
            background-color: rgb(255, 255, 255, 0.7);
            color: black;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 100px;
        }

        .date-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .date-tab {
            padding: 10px;
            width: 140px;
            background: #222;
            color: white;
            border-radius: 6px;
            text-align: center;

            text-decoration: none;
            transition: background 0.3s ease;

        }

        .date-tab.active {
            background: #e50914;
        }

        .time-slots {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .time-slot {
            border: none;
            padding: 0;
            background: none;
        }

        .time-slot a {
            display: block;
            padding: 10px 20px;
            background: #111;
            color: white;
            border-radius: 25px;
            text-decoration: none;
        }

        .time-slot a:hover {
            background: #e50914;
        }
    </style>
    <div class="container-film">
        @include('layouts.nav')
        <div class="film-describe">
            <h1 class="film-name" style="font-size: 60px;font-weight: bolder"> <span>{{ $phim->TenPhim }}</span>
                <span class="film-type-label">{{ $phim->DinhDang }}</span>
            </h1>
            <p class="film-infor">
                <span>{{ $phim->NuocSanXuat }}</span>
                <span>{{ $phim->ThoiLuong }} phút</span>
                <span>{{ $phim->DaoDien }}</span>
            </p>
            <p><strong>Mô tả:</strong> {{ $phim->MoTa }}</p>
        </div>


        <div class="film-select-schedule">
            {{-- Thanh chọn ngày chiếu --}}
            <div class="date-tabs">
                @foreach ($ngayChieu as $ngay)
                    <a href="{{ route('home.show', ['id' => $phim->MaPhim, 'ngay' => $ngay]) }}"
                        class="date-tab {{ request('ngay') == $ngay ? 'active' : '' }}">
                        <div>{{ \Carbon\Carbon::parse($ngay)->format('d/m') }}</div>
                        <div>{{ \Carbon\Carbon::parse($ngay)->translatedFormat('l') }}</div>
                    </a>
                @endforeach
            </div>

            {{-- Danh sách suất chiếu theo ngày --}}
            @if (!empty($suatTheoNgay) && count($suatTheoNgay) > 0)
                <div class="time-slots">
@foreach ($suatTheoNgay as $suat)
    <button class="time-slot">
        @auth
            <a href="{{  route('customer.ghe.index', $suat->MaSuatChieu) }}">
                {{ \Carbon\Carbon::parse($suat->NgayGioChieu)->format('H:i') }}
            </a>
        @else
            <a href="{{ route('login') }}">
                {{ \Carbon\Carbon::parse($suat->NgayGioChieu)->format('H:i') }}
            </a>
        @endauth
    </button>
@endforeach
</div>

            @elseif(request('ngay'))
                <p><em>Không có suất chiếu cho ngày này.</em></p>
            @endif

            <p style="color:rgba(255, 0, 0, 0.726); margin-top:15px">
                Lưu ý: Khán giả dưới 13 tuổi chỉ chọn suất chiếu kết thúc trước 22h và dưới 16 tuổi chỉ chọn trước 23h.
            </p>

            <a href="{{ route('home') }}">← Quay lại danh sách phim</a>
        </div>

    </div>

@endsection
