
@extends('admin.mylayout')

@section('title')
    Trang cá nhân
@endsection

@section('content')
  <form class="profiles" action="{{ route('admin.updateProfile') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="profile-head flex-row">
      <div class="pf-h-box-info flex-row">
        <div class="pf-h-box-img">
          <img id="profileImage" src="{{ $admin->img_path ?? url('assets/images/adminLogo.png') }}" alt="">
        </div>
        <div class="pf-h-info flex-col">
          <div class="flex-col" style="gap:5px">
            <h3 class="h-name">{{ $admin->username }}</h3>
            <h3 class="h-email">{{ $admin->email }}</h3>
            <span class="h-status" style="color: {{ $admin->status ? '#00ba3b' : '#ff4400' }}">{{ $admin->status ? 'Active' : 'Inactive' }}</span>
          </div>
          <input type="file" name="fileImg" id="avatarInput" style="display: none;" onchange="previewImage(event)">
          <button class="change-avt-btn flex-row" type="button" onclick="document.getElementById('avatarInput').click();">
            <i class='bx bxs-camera'></i>
            <span>Upload photo</span>
          </button>
        </div>
      </div>
      <div class="pf-h-role flex-row">
        <span>Quyền:</span>
        <p class="h-role">administrator</p>
      </div>
    </div>
    <div class="profile-content flex-col">
      <div class="pf-t-title">
        <h4>Chỉnh sửa thông tin</h4>
      </div>
      <div class="pf-t-form">
        <div class="frm-profile-edit flex-col">
          <div class="frm-box-info flex-row">
            <div class="frm-info flex-col">
              <label for="username">Tên người dùng</label>
              <input id="username" name="username" type="text" value="{{ $admin->username }}">
            </div>
            <div class="frm-info flex-col">
              <label for="email">Email</label>
              <input id="email" name="email" type="text" value="{{ $admin->email }}" disabled>
            </div>
          </div>
          <div class="pf-t-gender flex-row">
            <span>Giới tính:</span>
            <div class="box-gd flex-row">
              <input id="gender_male" name="gender" type="radio" value="1" {{ $admin->gender ? 'checked' : '' }}>
              <label for="gender_male">Nam</label>
            </div>
            <div class="box-gd flex-row">
              <input id="gender_female" name="gender" type="radio" value="0" {{ !$admin->gender ? 'checked' : '' }}>
              <label for="gender_female">Nữ</label>
            </div>
          </div>
          <div class="pf-t-descriptions flex-col">
            <label for="description">Mô tả</label>
            <textarea id="description" name="description">{{ $admin->description ?? '...' }}</textarea>
          </div>
          <div class="btn-box-save flex-col">
            @if(session('success'))
              <span id="notification-success" class="auth-notification" style="color: green;text-align: center;display: block;">{{ session('success') }}</span>
            @endif

            <button class="btn-save flex-row" type="submit">
              <i class='bx bxs-save'></i>
              <span>Lưu</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>

 
@endsection

@section('scripts')
  <script>
    function previewImage(event) {
      var reader = new FileReader();
      reader.onload = function() {
        var output = document.getElementById('profileImage');
        output.src = reader.result;
      }
      reader.readAsDataURL(event.target.files[0]);
    }
  </script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
      $(document).ready(function() {
          $('#notification-success').delay(3000).fadeOut();
      });
  </script>

@endsection
