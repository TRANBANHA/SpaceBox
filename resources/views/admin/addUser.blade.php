
@extends('admin.mylayout')


@section('content')
    <div class="box-form-add">
        <form class="form-addUser flex-col" action="{{ route('admin.addUserAction') }}" method="POST">
            @csrf
            <div class="box-ip flex-col">
                <h3>Thêm người dùng mới</h3>
            </div>
            <div class="box-ip flex-col">
                <input class="ip-add" type="text" id="username" name="username" placeholder="Nhập tên người dùng" required>
            </div>
            <div class="box-ip flex-col">
                <input class="ip-add" type="email" id="email" name="email" placeholder="Email người dùng" required>
            </div>
            <div class="box-ip flex-col">
                <select class="ip-add" id="role_id" name="role_id">
                <option selected>Quyền người dùng</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->role_id }}" required>{{ $role->role_name }}</option>
                @endforeach
                </select>
            </div>
            <div class="box-ip flex-col">
                <input class="ip-add" type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
            </div>
            <div class="box-ip flex-col">
                <input class="ip-add" type="password" id="password_confirmation" name="password_confirmation" placeholder="Nhập lại mật khẩu" required>
            </div>
            <div class="box-btn">
                <button type="submit">Thêm mới</button>
            </div>
        </form>
    </div>
@endsection