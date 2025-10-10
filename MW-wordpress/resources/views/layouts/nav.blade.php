    <style>
         .nav-item{
            padding: 0 10px;
            
        }
        .home-controll{
            position:fixed;
            width: 100%;
            top: -0px;
            box-shadow: rgb(48, 96, 163);
            z-index: 100;

        }

    </style>
   <script>
    document.addEventListener("DOMContentLoaded", function(){
        comming_soon =document.querySelectorAll(".comming_soon");
        comming_soon.forEach(function(link){
            link.addEventListener("click",function(){
                event.preventDefault(); // 
                alert("Chức năng đang phát triển, vui lòng quay lại sau!");
            })
            
        });
    })
   </script>

   <div class="home-controll">

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Cinema System</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">
                            <i class="fa-solid fa-house"></i>
                            Home
                        </a></li>

                        @guest
                            {{-- Khi chưa đăng nhập --}}
                            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Đăng Nhập</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Đăng ký</a></li>
                        @else
                            {{-- Khi đã đăng nhập --}}
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-user"></i>
                                    {{ Auth::user()->TenDangNhap }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item"  href="{{route('user.profile')}}">Thông tin cá nhân</a></li>
                                    <li><a class="dropdown-item " href="{{route('user.myTickets')}}">Vé đã mua</a></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Đăng xuất</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest

                       <li class="nav-item">
                        <a class="nav-link" href="{{ route('thongbao.index') }}">Kiểm tra vé sắp chiếu</a>
                            </li>
                    </ul>
                </div>
            </div>
        </nav>


    </div>