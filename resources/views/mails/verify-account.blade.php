<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực Email</title>
</head>
<body>
<div class="container" style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; font-family: Arial, sans-serif; color: #333;">
    <div class="email-title" style="text-align: center; margin-bottom: 20px;">
        <h3 style="margin: 0; font-size: 24px; color: #333;">Xin chào</h3>
        <h1 style="margin: 10px 0; font-size: 28px; color: #4070f4;">{{ $account->username }}</h1>
    </div>
    <p style="font-size: 16px; line-height: 1.5; color: #555;">Cảm ơn bạn đã đăng ký sử dụng SpaceBox!</p>
    <p style="font-size: 16px; line-height: 1.5; color: #555;">Vui lòng xác minh địa chỉ email của bạn bằng cách nhấp vào nút bên dưới:</p>
    <a href="{{ route('account.verify', $account->email )}}" style="display: inline-block; padding: 12px 24px; margin: 20px 0; font-size: 16px; color: #fff; background-color: #4070f4; text-decoration: none; border-radius: 6px; text-align: center;">Xác thực ngay</a>
    <p style="font-size: 16px; line-height: 1.5; color: #555;">Nếu bạn không tạo tài khoản, bạn không cần thực hiện thêm hành động nào nữa.</p>
</div>

</body>
</html>
