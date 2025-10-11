@extends('layouts.app')

@section('content')
    @include('layouts.nav')
    <div class="container">
        <h2 class="label">Chọn ghế cho suất chiếu:</h2>

        @if (session('error'))
            <div style="color: red;">{{ session('error') }}</div>
        @endif

        <form id="chon-ghe-form" method="POST" action="{{ route('customer.ghe.chon', $suatchieu->MaSuatChieu) }}">
            @csrf
            <div id="ghe-container" style="margin-bottom: 20px;">
                <p class="screen">Screen</p>

                @php
                    // Nhóm ghế theo hàng (chữ cái đầu)
                    $groupedGhe = [];
                    foreach ($danhSachGhe as $ghe) {
                        $row = preg_replace('/\d+/', '', $ghe); // lấy chữ cái đầu
                        $groupedGhe[$row][] = $ghe;
                    }
                @endphp

                @foreach ($groupedGhe as $row => $ghes)
                    <div style="margin-bottom: 5px;">
                        @foreach ($ghes as $soLuongGhe)
                            @php
                                $isBooked = in_array($soLuongGhe, $vedat);
                            @endphp
                            <button type="button" class="ghe-btn {{ $isBooked ? 'booked' : '' }}"
                                data-ghe="{{ $soLuongGhe }}" {{ $isBooked ? 'disabled' : '' }}>
                                {{ $soLuongGhe }}
                            </button>
                        @endforeach
                    </div>
                @endforeach
                <div class="note-ghe">
                    <span class="paid"> Đã đặt</span>
                    <span class="select">Đang chọn</span>
                    <span class="empty">Chưa được đặt</span>
                </div>
            </div>

            <button class="select-chair " type="submit">Xác nhận ghế</button>
        </form>

        <style>
            .select-chair{
                padding: 10px 20px;
                background-color: #2495d2;
                color: white;
                border: none;
                border-radius: 25px;
                cursor: pointer;
                font-size: 16px;
                ;

            }
            .note-ghe {
                color: white;
                margin-top: 10px;
                font-size: 18px;
            }

            .note-ghe span {
                margin-right: 15px;
            }

            .paid {
                background-color: #888;
                padding: 2px 6px;
                border-radius: 5px;

            }

            .select {
                background-color: #e74c3c;
                padding: 2px 6px;
                border-radius: 5px;
            }

            .empty {
                background-color: #4CAF50;
                padding: 2px 6px;
                border-radius: 5px;
            }

            .screen {
                background-color: #ccc;
                height: 40px;
                width: 90%;

                margin: 0 auto 20px auto;
                border-radius: 5px;
                text-align: center;
                line-height: 30px;
                font-weight: bold;
                color: #333;
                margin-bottom: 40px;

            }

            .label {
                color: white;
                font-size: 40px;
                font-weight: 900;
                margin-bottom: 20px;
                text-align: center;
            }

            body {
                background-image: url('/img/{{ $suatchieu->phim->DuongDanPoster }}');
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

            .container>* {
                position: relative;
                z-index: 1;
            }

            .container {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                /* min-height: 100vh; */
                height: 1100px;
                width: 1100px;
                margin-top: -100px;

            }

            #ghe-container {
                border: 1px solid rgba(0, 0, 0, 0.3);
                padding: 10px;
                border-radius: 5px;
                background-color: rgba(0, 0, 0, 0.3);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                max-width: 800px;
                text-align: center;
                margin-top: 200px;

            }

            .ghe-btn {
                width: 56px;
                height: 56px;
                margin: 4px;
                border: 2px solid #333;
                border-radius: 15px;
                background-color: #4CAF50;
                /* xanh trống */
                color: white;
                cursor: pointer;
            }

            .ghe-btn.booked {
                background-color: #888;
                /* xám đã đặt */
                cursor: not-allowed;
            }

            .ghe-btn.selected {
                background-color: #e74c3c;
                /* đỏ ghế đang chọn */
            }
        </style>

        <script>
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.ghe-btn');
    const form = document.getElementById('chon-ghe-form');
    let selectedGhe = [];

    buttons.forEach(btn => {
        btn.addEventListener('click', function () {
            const ghe = this.dataset.ghe;
            if (this.classList.contains('selected')) {
                this.classList.remove('selected');
                selectedGhe = selectedGhe.filter(g => g !== ghe);
            } else {
                this.classList.add('selected');
                selectedGhe.push(ghe);
            }
            console.log("Ghế đang chọn:", selectedGhe);
        });
    });

    form.addEventListener('submit', function (e) {
        // Xóa input cũ
        form.querySelectorAll('input[name="chon_ghe[]"]').forEach(i => i.remove());

        // Tạo input mới cho từng ghế
        selectedGhe.forEach(ghe => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'chon_ghe[]';
            input.value = ghe;
            form.appendChild(input);
        });

        if (selectedGhe.length === 0) {
            e.preventDefault();
            alert('Bạn chưa chọn ghế!');
        }
    });
});
</script>
    </div>
@endsection
