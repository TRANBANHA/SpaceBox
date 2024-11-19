@extends('admin.mylayout')

<!-- @section('title')
    Thay đổi mật khẩu
@endsection -->

@section('content')
    <div class="mainDiv">
        <div class="cardStyle">
            <form action="{{ route('admin.changePass')}} " method="post" name="signupForm" id="signupForm">
                @csrf
                @method('PUT')

                <img src="{{ url('assets/images/logo-app.png') }}" id="signupLogo"/>
                
                <h2 class="formTitle">
                    Đổi mật khẩu
                </h2>
                <div class="inputDiv">
                    <label class="inputLabel" for="password">Mật khẩu hiện tại</label>
                    <input type="password" id="passwordOld" name="passwordOld" required>
                    @error('passwordOld')
                        <small id="passwordOldError" class="auth-error" style="color: red">{{ $message }}</small>
                    @enderror
                </div>
                <div class="inputDiv">
                    <label class="inputLabel" for="password">Nhập mật khẩu mới</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="inputDiv">
                    <label class="inputLabel" for="confirmPassword">Nhập lại mật khẩu mới</label>
                    <input type="password" id="password_confirmation" name="password_confirmation">
                    @error('password')
                        <small id="password_confirmationError" class="auth-error">{{ $message }}</small>
                    @enderror
                </div>
                <div class="buttonWrapper">
                    @if(session('success'))
                        <span id="notification-success" class="auth-notification" style="color: green;text-align: center;display: block;">Đổi mật khẩu thành công</span>
                    @endif
                    <button type="submit" id="submitButton" class="submitButton pure-button pure-button-primary" >Xác nhận</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Thêm jQuery từ CDN -->
    
@endsection


@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#passwordOldError').delay(3000).fadeOut();
            $('#password_confirmationError').delay(3000).fadeOut();
            $('#notification-success').delay(3000).fadeOut();
        });
    </script>
@endsection