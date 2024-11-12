@extends('admin.mylayout')

@section('title')
    Danh sách người dùng
@endsection


@section('content')
  <div class="list-boxes">
    <div class="recent-list box">
        <div class="recent-list-table table-head flex-row">
          <div class="div-check"></div>
          <div class="user-box" style="text-align:left;">Thông tin người dùng</div>
          <div class="user-verify">Xác thực</div>
          <div class="user-role">Quyền</div>
          <div class="user-gender">Giới tính</div>
          <div class="user-status">Trạng thái</div>
          <div class="admin-action">Action</div>
        </div>
        @foreach ($users as $user)
        <div class="recent-list-table table-content flex-row">
            <div class="div-check">
              <input type="checkbox">
            </div>
            <div class="user-box flex-row">
              <div class="user-avt">
                <img src="{{ url('assets/images/' . ($user->gender ? 'male.png' : 'female.png')) }}" alt="User Avatar">
              </div>
              <div class="user-info flex-col">
                <div class="info-name">{{ $user->username }}</div>
                <div class="info-email">{{ $user->email }}</div>
              </div>
            </div>
            <div class="user-verify" style="color: {{ $user->email_verified_at ? '#898989' : '#ff0000' }}">{{ $user->email_verified_at ?? 'Chưa xác thực' }}</div>
            <div class="user-role">
              {{ ['Moderate User', 'Normal User'][$user->role_id - 2] ?? 'Unknown' }}
            </div>

            <div class="user-gender">{{ $user->gender ? 'Nam' : 'Nữ' }}</div>
            <div class="user-status" style="font-weight: bold; color: {{ $user->status ? '#00ed3e' : '#ff4400' }}">{{ $user->status ? 'Active' : 'Inactive' }}</div>
            <div class="admin-action">
              <i class='bx bx-dots-vertical-rounded'></i>
              <!-- Dropdown Menu -->
              <div class="action-menu">
                <ul>
                  <li><a href="#">Reset Password</a></li>
                  <li><a href="#">Khoá tài khoản</a></li>
                  <li><a href="#">Xóa tài khoản</a></li>
                </ul>
              </div>
            </div>     
          </div>

          @endforeach    
    </div>
  </div>
@endsection


@section('scripts')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    // Lắng nghe sự kiện click vào biểu tượng 3 chấm để mở menu
    $(document).on('click', '.admin-action i', function() {
      $(this).siblings('.action-menu').toggle(); // Hiển thị hoặc ẩn menu
    });

    // Đóng menu khi người dùng click bên ngoài
    $(document).on('click', function(e) {
      if (!$(e.target).closest('.admin-action').length) {
        $('.action-menu').hide(); // Ẩn menu nếu click ngoài
      }
    });
  </script>
@endsection
