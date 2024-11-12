
<x-my-layout>
    <x-slot name="title">
        Quên mật khẩu
    </x-slot>
    <x-slot name="linkcss">
        <link rel="stylesheet" href="{{ url('assets/css/auth/auth.css') }}">
        <link rel="stylesheet" href="{{ url('assets/css/auth/modal-notifications.css') }}">
    </x-slot>

    @include('components.header')
    <div class="wrapper flex-row">
        <div class="auth-form">
           
            <h2>Yêu cầu cấp lại mật khẩu</h2>
            <form  id="forgotPassForm" action="{{ route('account.sendResetPass') }}" method="POST" autocomplete="on">
                @csrf

                <div class="input-box">
                    <input value="{{ old('email') }}" id="email" name="email" class="form-control" type="email" placeholder="Nhập email của bạn" required>
                </div>
                @error('email')
                    <small id="emailError" class="auth-error">{{ $message }}</small>
                @enderror
                <div class="button-box">
                    <input type="Submit" value="Gửi yêu cầu">
                </div>
            </form>
        </div>
        <div class="content-demo">
            <img src="{{ url('assets/images/demo.jpg') }}" alt="">
        </div>
    </div>

    @if (session('success'))
        @include('auth.modal-success')
    @endif
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#emailError').delay(3000).fadeOut();
        });
    </script>
</x-my-layout>