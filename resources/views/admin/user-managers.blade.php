@extends('admin.mylayout')

<!-- @section('title')
    Danh sách người dùng
@endsection -->


@section('content')
  <div class="list-boxes">
    <div class="recent-list box">
      <div class="recent-list-head flex-row">
        <a class="btn-addUser flex-row" href="{{ route('admin.addUserForm')}}">
          <i class='bx bxs-user-plus'></i>
          <span>Add new</span>
        </a>
        <div class="notifi-box">
          @if(session('admin-success'))
            <span id="notification-success" class="auth-notification" style="color: #00ff00;text-align: center;display: block;">{{ session('admin-success') }}</span>
          @endif          
        </div>
       
      </div>
      <div class="recent-list-table table-head flex-row">
        <div class="div-check flex-row">
          @if(count($users) > 1)
            <input type="checkbox">
            <span>All</span>
          @endif
          
        </div>
        <div class="user-box" style="text-align:left;">Thông tin người dùng</div>
        <div class="user-verify">Xác thực</div>
        <div class="user-role">Quyền</div>
        <div class="user-gender">Giới tính</div>
        <div class="user-status">Trạng thái</div>
        <div class="admin-action">Action</div>
      </div>
      @foreach ($users as $user)
        <div class="recent-list-table table-content flex-row">
          <div class="div-check flex-row">
            <input name="user_id[]" type="checkbox" class="select-user" value="{{ $user->user_id }}">
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
          <div class="user-status" style="font-weight: bold; color: {{ $user->status ? '#00ed3e' : '#ff1500' }}">{{ $user->status ? 'Active' : 'Inactive' }}</div>
          <div class="admin-action">
            <i class='bx bx-dots-vertical-rounded'></i>
            <!-- Dropdown Menu -->
            <div class="action-menu">
              <ul>
                <li><a href="{{ route('admin.updateUserForm', $user->user_id ) }}">Sửa thông tin</a></li>
                <li><a href="#" class="open-modal-sendEmail-resetPass">Reset password</a></li>
                @if($user->status)
                  <form action="{{ route('admin.lockAccountUser', $user->user_id) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <li><button type="submit" name="lockAccountUser">Khoá tài khoản</button></li>
                  </form>
                @else
                <form action="{{ route('admin.unlockAccountUser', $user->user_id) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <li><button type="submit" name="unlockAccountUser">Mở khoá tài khoản</button></li>
                  </form>
                @endif
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
            <p class="modal-note">Mật khẩu mới sẽ được gửi tới email của người dùng</p>
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
      <!-- Admin functions -->   
      <div class="box-functions">
        <form id="deleteUsersForm" action="{{ route('admin.deleteUsers') }}" method="POST">
          @csrf
          @method('DELETE')
          
          <input type="hidden" name="user_ids[]" id="user_ids">
          <button class="btn-box flex-row btn-delete-selected" type="submit">
              <i class='bx bx-trash'></i>
              <span>Xóa tài khoản</span>
          </button>
        </form>
      </div>
    </div>
  </div>
   <!-- Modal section -->
    
@endsection


@section('scripts')

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
        $('#notification-success').delay(3000).fadeOut();
    });
  </script>

  <!-- Lắng nghe sự kiện click vào checkbox -->
  <script>
    $(document).ready(function () {
      
      $(document).on('change', '.table-head .div-check input[type="checkbox"]', function () {
        const isChecked = $(this).is(':checked');
        // Chọn hoặc bỏ chọn tất cả checkbox trong danh sách
        $('.table-content .div-check input[type="checkbox"]').prop('checked', isChecked);
        // Hiển thị hoặc ẩn phần Admin functions
        if (isChecked) {
            $('.box-functions').fadeIn();
        } else {
            $('.box-functions').fadeOut();
        }
      });
      $(document).on('change', '.div-check input[type="checkbox"]', function () {
          // Kiểm tra xem có checkbox nào được chọn không
          const hasChecked = $('.div-check input[type="checkbox"]:checked').length > 0;
          // Hiển thị hoặc ẩn phần Admin functions dựa trên trạng thái checkbox
          if (hasChecked) {
              $('.box-functions').fadeIn(); // Hiển thị
          } else {
              $('.box-functions').fadeOut(); // Ẩn
          }
      });

        
    });
  </script>

  <!-- Lắng nghe sự kiện click vào nút "Xóa tài khoản" -->
  <script>
    $(document).ready(function () {
    // Sự kiện click vào nút "Xóa tài khoản"
      $('.btn-delete-selected').on('click', function (e) {
        e.preventDefault(); // Ngăn chặn form submit mặc định

        // Lấy tất cả checkbox được chọn
        const checkboxes = document.querySelectorAll('input[name="user_id[]"]:checked');
        // Tạo một mảng chứa ID
        const selectedIds = Array.from(checkboxes).map(checkbox => checkbox.value);

        // Kiểm tra nếu không có ID nào được chọn
        if (selectedIds.length === 0) {
            alert('Vui lòng chọn ít nhất một tài khoản để xóa.');
            return;
        }

        // Gửi mảng ID qua input ẩn
        $('#user_ids').val(selectedIds);

        // Gửi form
        $('#deleteUsersForm').submit();
      });
    });

  </script>



  <!-- Lắng nghe sự kiện click vào biểu tượng 3 chấm để mở menu -->
  <script>
    $(document).ready(function () {
        $(document).on('click', '.admin-action i', function (e) {
            e.stopPropagation(); // Ngăn chặn sự kiện lan ra ngoài

            // Ẩn tất cả các menu khác
            $('.action-menu').not($(this).siblings('.action-menu')).hide();
          
            // Hiển thị hoặc ẩn menu của dòng được click
            $(this).siblings('.action-menu').toggle();
        });

        // Đóng tất cả các menu khi người dùng click bên ngoài
        $(document).on('click', function () {
            $('.action-menu').hide(); // Ẩn tất cả các menu
        });
    }); 

  </script>



  <!-- Sử dụng sự kiện click để mở modal reset mật khẩu -->
  <script>
    $(document).on('click', '.open-modal-sendEmail-resetPass', function (e) {
        e.preventDefault();
        
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
