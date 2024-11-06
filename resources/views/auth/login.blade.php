
<x-my-layout>
    <x-slot name="linkcss">
        <link rel="stylesheet" href="{{ url('assets/css/auth/auth.css') }}">
        <link rel="stylesheet" href="{{ url('assets/css/auth/modal-notifications.css') }}">
    </x-slot>

    @include('components.header')
    <div class="wrapper flex-row">
        <div class="auth-form">
           
            <h2>Đăng nhập</h2>
            <form  id="loginForm" action="{{ route('account.login.auth') }}" method="POST">
                @csrf
                <div class="input-box">
                    <input value="{{ old('email')}}" id="email" name="email" class="form-control" type="email" placeholder="Nhập email của bạn" required>
                </div>
                <div class="input-box">
                    <input id="password" name="password" class="form-control"  type="password" placeholder="Nhập mật khẩu" required>
                    <i class="fa-regular fa-eye"></i>
                </div>
                <div class="button-box">
                    <input type="Submit" value="Đăng Nhập">
                </div>
                <div class="text">
                    <h3>Chưa có tài khoản? <a href="{{ route('account.register') }}">Đăng ký ngay</a></h3>
                </div>
            </form>
        </div>
        <div class="content-demo">
            <img src="{{ url('assets/images/demo.jpg') }}" alt="">
        </div>
    </div>
    @if(session('success'))
        @include('auth.modal-success')
    @endif
    @if(session('errors'))
        @include('auth.modal-errors')
    @endif
    
</x-my-layout>