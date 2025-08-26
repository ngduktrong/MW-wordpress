<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Test Laravel</title>
</head>
<body>
    <h1>Kết quả từ Controller</h1>

    <p>Tuổi: {{ $age }} → {{ $status }}</p>

    <h3>Dãy số (tạo trong Controller):</h3>
    <ul>
        @foreach ($numbers as $num)
            <li>{{ $num }}</li>
        @endforeach
    </ul>

    <h3>Kỹ năng (foreach trong Controller, đưa qua View):</h3>
    <ul>
        @foreach ($skillList as $skill)
            <li>{{ $skill }}</li>
        @endforeach
    </ul>
</body>
</html>
