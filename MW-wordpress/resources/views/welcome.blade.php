
@extends('layouts.app')
@section('content')


    <style>
        body {
            background-image: url('/img/riri-williams-3840x2160-22692.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
             height: min(1100px)

        }


.main-content {
  flex: 1; /* Chiếm phần còn lại giữa navbar và footer */
}


        .welcome-content {
            color: white;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
            font-size: 2rem;
            font-weight: bold;
            display: flex;
            flex-direction: column;

            justify-content: center;
            align-items: center;
            height: 85vh;
            text-align: center;

        }


        .nav-item{
            margin: 5px 10px;
            font-size: 20px;
        }
        .head-controll{
            position:fixed;
            width: 100%;
            box-shadow: rgb(48, 96, 163);

        }
        .btn{
            padding: 10px 20px;
            background-color: rgb(255, 255, 255);
            color: black;
            border-radius: 50px;
            margin-top: 20px;
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


