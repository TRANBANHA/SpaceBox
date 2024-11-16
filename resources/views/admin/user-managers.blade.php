@extends('admin.mylayout')

@section('title')
    Danh sách người dùng
@endsection


@section('content')
  <div class="list-boxes">
    <d class="recent-list box">
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
                <img src="{{ $user->img_path ?? 'https://res.cloudinary.com/dy6y1gpgm/image/upload/v1731680383/male_q2q91r.png' }}" alt="User Avatar">
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
                  <!-- <li class="open-modal-sendEmail-resetPass">Reset Password</li> -->
                  <li><a href="#" class="open-modal-sendEmail-resetPass">Reset Password</a></li>
                  <li><a href="#" class="open-modal-">Phân quyền</a></li>
                  <li><a href="#">Xóa tài khoản</a></li> 
                </ul>
              </div>
            </div>     
          </div>
          <!-- Modal send email reset password -->
          <div class="modal-sendEmail-resetPass">
            <div class="modal-sendEmail-content flex-col">
              <div class="modal-head flex-row">
                <h2 class="modal-h-title">Cấp mật khẩu mới</h2>
                <i class='bx bx-x-circle'></i>
              </div>
              <p class="modal-note">Mật khẩu mới sẽ được gửi tới email của người dùng:</p>
              <form id="resetPassForm" action="{{ route('admin.sendResetPass') }}" method="POST">
                  @csrf
                  <div class="input-box">
                      <input value="{{ $user->email }}" id="email" name="email" class="form-control" type="email" readonly>
                  </div>
                  <div class="button-box">
                      <button type="Submit">Xác nhận</button>
                  </div>
              </form>
            </div>
          </div>
        @endforeach    
        <div class="box-functions">
          <div class="box-functions-left">
            <div class="select-all">
              <input type="checkbox">
              <span>Chọn tất cả</span>
            </div>
            <div class="box-functions-list">
              <span>Chọn thao tác</span>
              <select name="functions" id="functions">
                <option value="delete">Xóa tài khoản</option>
                <option value="active">Kích hoạt tài khoản</option>
                <option value="inactive">Vô hiệu hóa tài khoản</option>
              </select>
            </div>
            <div class="box-functions-apply">
              <button>Áp dụng</button>
            </div>
          </div>
          <div class="box-functions-right">
            <div class="box-functions-pagination">
              <span>1-10 of 100</span>
              <div class="box-functions-pagination-list">
                <span><i class='bx bxs-chevron-left'></i></span>
                <span>1</span>
                <span>2</span>
                <span>3</span>
                <span>4</span>
                <span>5</span>
                <span><i class='bx bxs-chevron-right'></i></span>
              </div>
            </div>
          </div>
        </div>
      </d>
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

  <script>
    // Sử dụng sự kiện click để mở modal reset mật khẩu
    $(document).on('click', '.open-modal-sendEmail-resetPass', function (e) {
        e.preventDefault(); // Ngăn chặn hành động mặc định của thẻ <a>
        
        // Lấy thông tin email của người dùng từ dòng được click
        const userEmail = $(this).closest('.recent-list-table').find('.info-email').text().trim();

        // Hiển thị modal
        const modal = $('.modal-sendEmail-resetPass');
        modal.show();

        // Cập nhật email vào input trong modal
        modal.find('#email').val(userEmail);
    });

    // Đóng modal khi bấm vào nút đóng
    $(document).on('click', '.modal-sendEmail-resetPass .bx-x-circle', function () {
        $('.modal-sendEmail-resetPass').hide();
    });

    // Đóng modal khi click ra ngoài modal
    $(window).on('click', function (e) {
        if ($(e.target).hasClass('modal-sendEmail-resetPass')) {
            $('.modal-sendEmail-resetPass').hide();
        }
    });

  </script>
@endsection
