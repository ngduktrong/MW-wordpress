@extends('layouts.app')

@section('content')
@include('layouts.nav')
<style>
    body {
        background-image: url('/img/{{ $suatchieu->phim->DuongDanPoster }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .container {
        display: flex;
        justify-content: center;
        align-items: center;
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

    .ve-infor {
        color: rgb(0, 0, 0);
        padding: 30px;
        width: 500px;
        display: flex;
        flex-direction: column;
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 15px;
    }

    .confirmBtn {
        background-color: #1fb9d4;
        color: white;
        border: none;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 0px 0;
        cursor: pointer;
        border-radius: 25px;
        position: relative;
        z-index: 3;
    }

    .actions {
        display: flex;
        justify-content: end;
        align-items: center;
        margin-top: 20px;
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 25px;
        width: fit-content;
        padding-left: 40px;
        margin-left: 160px;
    }

    .actions>* {
        margin-left: 10px;
    }
    .payment-success-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.8);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        color: white;
        font-size: 24px;
        text-align: center;
    }

    .success-icon {
        font-size: 80px;
        color: #4CAF50;
        animation: pop 0.6s ease;
    }

    @keyframes pop {
        0% { transform: scale(0); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>

<div class="container">
    <div class="ve-infor">
        <h2 style="text-align: center">Xác nhận vé</h2>

        <p>Phim: <b>{{ $suatchieu->phim->TenPhim }}</b></p>
        <p>Phòng: {{ $suatchieu->phongChieu->TenPhong }}</p>
        <p>Suất chiếu: {{ $suatchieu->NgayGioChieu }}</p>
        <p>Ghế đã chọn: {{ implode(', ', $chonGhe) }}</p>
        <p>Tổng tiền tạm: {{ count($chonGhe) * 50000 }} VND</p>

        @if(session('error'))
            <p style="color:red">{{ session('error') }}</p>
        @endif

        <div class="actions">
            <a class="backBtn" href="{{ route('customer.ghe.index', $suatchieu->MaSuatChieu) }}">Quay lại</a>

            <form id="paymentForm" method="POST" action="{{ route('ve.book') }}">
                @csrf
                <button class="confirmBtn btn-shadow" type="submit">Thanh Toán</button>
            </form>
        </div>
    </div>
</div>
<div class="payment-success-overlay" id="successOverlay">
    <div class="success-icon">✅</div>
    <p>Thanh toán thành công!</p>
    <p>Đang chuyển hướng...</p>
</div>

<script>
    const paymentForm = document.getElementById('paymentForm');
    const overlay = document.getElementById('successOverlay');

    paymentForm.addEventListener('submit', async function(event) {
        event.preventDefault(); // Ngăn submit thật, để xử lý AJAX
        overlay.style.display = 'flex'; // Hiện overlay

        const formData = new FormData(paymentForm);

        try {
            const response = await fetch(paymentForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            // Laravel trả redirect -> fetch không tự theo dõi redirect, nên ta lấy URL từ response
            if (response.redirected) {
                const redirectUrl = response.url;
                setTimeout(() => {
                    window.location.href = redirectUrl;
                }, 3000);
            } else {
                overlay.style.display = 'none';
                alert("❌ Có lỗi xảy ra khi thanh toán!");
            }
        } catch (error) {
            overlay.style.display = 'none';
            alert("⚠️ Lỗi kết nối server!");
        }
    });
</script>

@endsection
