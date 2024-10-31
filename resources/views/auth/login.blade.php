
<x-my-layout>
    <x-slot name="linkcss">
        <link rel="stylesheet" href="{{ url('assets/css/auth/signup.css') }}">
    </x-slot>

    @include('components.header')
    <div class="wrapper flex-row">
        <div class="auth-form">
            <h2>Đăng nhập</h2>
            <form action="#" method="POST">
                <div class="input-box">
                    <input id="email" name="email" type="text" placeholder="Nhập email của bạn" required>
                </div>
                <div class="input-box">
                    <input id="password" name="password" type="password" placeholder="Nhập mật khẩu" required>
                </div>
                <div class="input-box button">
                    <input type="Submit" value="Đăng Nhập">
                </div>
                <div class="text">
                    <h3>Chưa có tài khoản? <a href="{{ route('auth.register') }}">Đăng ký ngay</a></h3>
                </div>
            </form>
        </div>
        <div class="content-demo">
            <img src="{{ url('assets/images/demo.jpg') }}" alt="">
        </div>
    </div>
</x-my-layout>