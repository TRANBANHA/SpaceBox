
<x-my-layout>
    <x-slot name="linkcss">
        <link rel="stylesheet" href="{{ url('assets/css/auth/signup.css') }}">
    </x-slot>

    @include('components.header')
    <div class="wrapper flex-row">
        <div class="auth-form">
            <h2>Đăng ký tài khoản</h2>
            <form action="#">
                <div class="input-box">
                    <input id="name" name="name" type="text" placeholder="Nhập Họ và Tên" required>
                </div>
                <div class="input-box">
                    <input id="email" name="email" type="text" placeholder="Nhập Email mà bạn sử dụng" required>
                </div>
                <div class="input-box">
                    <input id="password" name="password" type="password" placeholder="Nhập mật khẩu" required>
                </div>
                <div class="input-box">
                    <input id="re-password" name="re_password" type="password" placeholder="Nhập lại mật khẩu" required>
                </div>
                <div class="input-box button">
                    <input type="Submit" value="Đăng Ký">
                </div>
                <div class="text">
                    <h3>Đã có tài khoản? <a href="{{ route('auth.login') }}">Đăng nhập ngay</a></h3>
                </div>
            </form>
        </div>
        <div class="content-demo">
            <img src="{{ url('assets/images/demo.jpg') }}" alt="">
        </div>
    </div>
</x-my-layout>