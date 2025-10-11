<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>danh sach nguoi dung</h1>
    <ul>
        @foreach ($users as $user)
            <li>
                <strong> Tên khách hàng: {{ $user->HoTen }}<br></strong> 
                Địa chỉ email: {{ $user->Email }}
                <br>
                Số điện thoại : {{ $user->SoDienThoai }}

            </li>
        
        @endforeach
    </ul>
</body
</html>