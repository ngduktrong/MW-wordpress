<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>WELCOME</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>



    <div class="d-flex flex-column min-vh-100">
  <main class="flex-grow-1">
    {{-- nội dung cần hiển thị sẽ ở đây --}}
    @yield('content')
  </main>
  <footer class="text-light text-center py-3">
    <p>© 2025 Rạp phim TML

    </p>
  </footer>
</div>



</body>
</html>
