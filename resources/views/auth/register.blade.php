
<x-my-layout>
    <x-slot name="linkcss">
        <link rel="stylesheet" href="{{ url('assets/css/auth/signup.css') }}">
    </x-slot>

    @include('components.header')
    <div class="wrapper flex-row">
        <div class="auth-form">
            <h2>Đăng ký tài khoản</h2>
            <form action="{{ route('spacebox.register.auth') }}" method="POST">
                @csrf
                <div class="input-box">
                    <input id="username" name="username" class="form-control" type="text" placeholder="Nhập tên người dùng" required>
                </div>
                <div class="input-box">
                    <input id="email" name="email" class="form-control" type="email" placeholder="Nhập email của bạn" required>
                </div>
                <div class="input-box">
                    <input id="password" name="password" class="form-control" type="password" placeholder="Nhập mật khẩu" required>
                </div>
                <!-- <div class="input-box">
                    <input id="re-password" name="re_password" class="form-control"> type="password" placeholder="Nhập lại mật khẩu" required>
                </div> -->
                <div class="input-box button">
                    <input type="Submit" value="Đăng Ký">
                </div>
                <div class="text">
                    <h3>Đã có tài khoản? <a href="{{ route('spacebox.login') }}">Đăng nhập ngay</a></h3>
                </div>
            </form>
        </div>
        <div class="content-demo">
            <img src="{{ url('assets/images/demo.jpg') }}" alt="">
        </div>
    </div>
</x-my-layout>