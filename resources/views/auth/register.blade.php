
<x-my-layout>
    <x-slot name="linkcss">
        <link rel="stylesheet" href="{{ url('assets/css/auth/auth.css') }}">
    </x-slot>

    @include('components.header')
    <div class="wrapper flex-row">
        <div class="auth-form">
            <h2>Đăng ký tài khoản</h2>
            <form id="registerForm" action="{{ route('account.register.auth') }}" method="POST">
                @csrf
                <div class="input-box">
                    <input id="username" name="username" class="form-control" type="text" placeholder="Nhập tên người dùng" required>
                </div>

                <div class="input-box">
                    <input id="email" name="email" class="form-control" type="email" placeholder="Nhập email của bạn" required>
                </div>
                <small id="emailError" class="auth-error">Email không hợp lệ</small>
                
                <div class="input-box">
                    <input id="password" name="password" class="form-control" type="password" placeholder="Nhập mật khẩu" required>
                    <i class="fa-regular fa-eye"></i>
                </div>

                <div class="input-box">
                    <input id="re-password" name="re-password" class="form-control" type="password" placeholder="Nhập lại mật khẩu" required>
                    <i class="fa-regular fa-eye"></i>
                </div>
                <small id="passwordError" class="auth-error">Mật khẩu không trùng khớp</small>

                <div class="button-box">
                    <input type="Submit" value="Đăng Ký">
                </div>
                <div class="text">
                    <h3>Đã có tài khoản? <a href="{{ route('account.login') }}">Đăng nhập ngay</a></h3>
                </div>
            </form>
        </div>
        <div class="content-demo">
            <img src="{{ url('assets/images/demo.jpg') }}" alt="">
        </div>
    </div>
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(event) {
            const password = document.getElementById('password').value;
            const rePassword = document.getElementById('re-password').value;

            const email = document.getElementById('email').value;
            const emailError = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');


            //Kiểm tra định dạng email
            const emailPattern = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
            if (!emailPattern.test(email)) {
                event.preventDefault();
                emailError.style.display = 'block';
                setTimeout(() => {
                    emailError.style.display = 'none'; 
                }, 5000);
            } else {
                email.style.background = 'none'; 
            }

            // Kiểm tra mật khẩu
            if (password !== rePassword) {
                event.preventDefault();
                passwordError.style.display = 'block'; 
                setTimeout(() => {
                    passwordError.style.display = 'none'; 
                }, 5000);
            } else {
                
                passwordError.style.display = 'none';
            }
        });
    </script>
</x-my-layout>