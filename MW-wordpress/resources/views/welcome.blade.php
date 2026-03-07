
@extends('layouts.app')
@section('content')


    <style>
    body {
        background-image: url('/img/riri-williams-3840x2160-22692.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 100vh;
        margin: 0;
    }

    .main-content {
        flex: 1;
    }

    .welcome-content {
        color: white;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8);
        font-size: 2rem;
        font-weight: bold;

        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;

        height: 85vh;
        text-align: center;
        padding-top: 60px;
    }

    .nav-item {
        margin: 0 10px;
        font-size: 18px;
    }

    .nav-link {
        transition: 0.3s;
    }

    .nav-link:hover {
        color: #ffc107 !important;
    }

    .head-controll {
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    .btn {
        padding: 12px 28px;
        background-color: white;
        color: black;
        border-radius: 50px;
        margin-top: 25px;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
    }

    .btn:hover {
        background-color: #ffc107;
        color: black;
        transform: scale(1.05);
    }
</style>
</head>

<body>
    <div class="head-controll">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="">Cinema System</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Đăng Nhập</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Đăng ký</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Introduce Author</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <form method="GET" action="{{ route('home') }}">
        <div class="welcome-content">
            <h1>
                <strong>Chào Mừng Bạn Đến Với Rạp Phim</strong>
            </h1>
            <button type="submit" class="btn btn-shadow">Xem Phim</button>
        </div>

    </form>
@endsection


