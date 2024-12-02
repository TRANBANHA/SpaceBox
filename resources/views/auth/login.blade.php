
<x-my-layout>
    <x-slot name="title">
        Đăng nhập
    </x-slot>
    <x-slot name="linkcss">
        <link rel="stylesheet" href="{{ url('assets/css/auth/auth.css') }}">
        <link rel="stylesheet" href="{{ url('assets/css/auth/modal-notifications.css') }}">
    </x-slot>

    @include('components.header')
    <div class="wrapper flex-row">
        <div class="auth-form">
           
            <h2>Đăng nhập</h2>
            <form  id="loginForm" action="{{ route('account.login.auth') }}" method="POST" autocomplete="on">
                @csrf
                <div class="input-box">
                    <input value="{{ old('email') }}" id="email" name="email" class="form-control" type="email" placeholder="Nhập email của bạn" required>
                </div>
                <div class="input-box">
                    <input id="password" name="password" class="form-control"  type="password" placeholder="Nhập mật khẩu" required>
                    <i id="togglePassword" class="fa-regular fa-eye"></i>
                </div>
                <div class="action-box flex-row">
                    <div class="flex-row">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Nhớ mật khẩu</label>
                    </div>
                    <a href="{{ route('account.forgotPassForm') }}" class="forgot-pass">Quên mật khẩu</a>
                </div>
                <div class="button-box">
                    <input type="Submit" value="Đăng Nhập">
                </div>
                <div class="text">
                    <h3>Chưa có tài khoản? <a href="{{ route('account.register') }}">Đăng ký ngay</a></h3>
                </div>
                <div class="btn-login-ser flex-row">
                    <a href="{{ route('account.auth.google') }}" class="btn-shared btn-google">
                        <i class='bx bxl-google custom-icon mr-3'></i>
                        Google
                    </a>
                    <a href="#" class="btn-shared btn-facebook">
                        <i class='bx bxl-facebook custom-icon mr-3'></i>
                        Facebook
                    </a>
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
    
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            var passwordField = document.getElementById('password');
            var type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            
            // Thay đổi biểu tượng của mắt
            this.classList.toggle('fa-eye-slash');
        });
    </script>
    
</x-my-layout>