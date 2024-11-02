<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f6f6;
            color: #333;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .container .email-title{
            width: 100%;
            display: flex;
            flex-direction: row;
            gap: 15px;
            align-items: center;
            justify-content: center;
        }

        h1 {
            color: #0097ff;
            text-transform: uppercase;
        }
        .button {
            display: inline-block;
            padding: 10px 15px;
            color: #fff;
            background-color: #0097ff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="email-title">
            <h3>Xin chào</h3>
            <h1>{{ $account->username }}</h1>
        </div>
        <p>Cảm ơn bạn đã đăng ký sử dụng SpaceBox!</p>
        <p>Vui lòng xác minh địa chỉ email của bạn bằng cách nhấp vào nút bên dưới:</p>
        <a href="{{ route('account.verify', $account->email )}} " class="button">Xác thực ngay</a>
        <p>Nếu bạn không tạo tài khoản, bạn không cần thực hiện thêm hành động nào nữa.</p>
    </div>
</body>
</html>
