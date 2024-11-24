@extends('admin.mylayout')


@section('content')
  <div class="list-boxes">
    <div class="recent-list recent-list-room box">
      <div class="recent-list-head flex-row">
        <a class="btn-addRoom flex-row" href="#">
          <i class='bx bxs-group'></i>
          <span>Add new</span>
        </a>
        <!-- Add Room -->
        <div class="modal-addRoom">
          <div class="modal-addRoom-content flex-col">
            <div class="modal-head flex-row">
              <h2 class="modal-h-title">Thêm mới nhóm chat</h2>
              <i class='bx bx-x-circle'></i>
            </div>
            <form id="addRoomForm" action="{{ route('admin.addRoom') }}" method="POST">
                @csrf
                <div class="input-box">
                    <input id="room_name" name="room_name" class="form-control" type="text" placeholder="Nhập tên group">
                </div>
                <div class="button-box">
                    <button type="Submit">Xác nhận</button>
                </div>
            </form>
          </div>
        </div>
        <div class="notifi-box">
          @if(session('admin-success'))
            <span id="notification-success" class="auth-notification" style="color: #00ff00;text-align: center;display: block;">{{ session('admin-success') }}</span>
          @endif          
        </div>
       
      </div>
      <div class="recent-list-table table-head flex-row">
        <div class="div-check flex-row">
          @if(count($rooms) > 1)
            <input type="checkbox">
            <span>All</span>
          @endif
          
        </div>
        <div class="room-box" style="text-align:left;">Thông tin phòng chat</div>
        <div class="room-created-at">Ngày tạo</div>
        <div class="room-created-by">Người tạo</div>
        <div class="admin-action">Action</div>
      </div>
      @foreach ($rooms as $room)
        <div class="recent-list-table table-content flex-row">
          <div class="div-check flex-row">
            <input name="room_id[]" type="checkbox" class="select-room" value="{{ $room->room_id }}">
          </div>
          <div class="room-box flex-row">
            <div class="room-avt">
              <img src="{{ $room->avt_path }}" alt="room Avatar">
            </div>
            <div class="room-info flex-col">
              <div class="info-name">{{ $room->room_name }}</div>
            </div>
          </div>
          <div class="room-created-at">{{ now() }}</div>
          <div class="room-created-by">{{ $room->created_by }}</div>

          <div class="admin-action">
            <i class='bx bx-dots-vertical-rounded'></i>
            <!-- Dropdown Menu -->
            <div class="action-menu">
              <ul>
                <li><a href="#" class="btn-edit-room" data-room-id="{{ $room->room_id }}">Sửa thông tin</a></li>
              </ul>
            </div>
          </div>     
        </div>
      <!-- Update Room -->
      <div class="modal-updateRoom" id="modal-room-{{ $room->room_id }}">
          <div class="modal-updateRoom-content flex-col">
            <div class="modal-head flex-row">
              <h2 class="modal-h-title">Sửa tên group</h2>
              <i class='bx bx-x-circle'></i>
            </div>
            <form id="updateRoomForm" action="{{ route('admin.updateRoom', $room->room_id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="input-box">
                    <input id="room_name" name="room_name" class="form-control" type="text" value="{{ $room->room_name }}">
                </div>
                <div class="button-box">
                    <button type="Submit">Lưu</button>
                </div>
            </form>
          </div>
        </div>
      @endforeach 
      <!-- Admin functions -->   
      <div class="box-functions">
        <form id="deleteRoomsForm" action="{{ route('admin.deleteRoom') }}" method="POST">
          @csrf
          @method('DELETE')
          
          <input type="hidden" name="room_ids[]" id="room_ids">
          <button class="btn-box flex-row btn-delete-selected" type="submit">
              <i class='bx bx-trash'></i>
              <span>Xóa phòng chat</span>
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
        const checkboxes = document.querySelectorAll('input[name="room_id[]"]:checked');
        // Tạo một mảng chứa ID
        const selectedIds = Array.from(checkboxes).map(checkbox => checkbox.value);

        // Kiểm tra nếu không có ID nào được chọn
        if (selectedIds.length === 0) {
            alert('Vui lòng chọn ít nhất một tài khoản để xóa.');
            return;
        }

        // Gửi mảng ID qua input ẩn
        $('#room_ids').val(selectedIds);

        // Gửi form
        $('#deleteRoomsForm').submit();
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



  <!-- Sử dụng sự kiện click để mở modal add Room-->
  <script>
    // Lấy các phần tử DOM
    const modal = document.querySelector('.modal-addRoom'); // Modal container
    const addRoomBtn = document.querySelector('.btn-addRoom'); // Nút mở modal
    const closeModalBtn = document.querySelector('.bx-x-circle'); // Nút đóng modal

    // Hàm mở modal
    function openModal() {
      modal.style.display = 'flex'; // Hiển thị modal
    }

    // Hàm đóng modal
    function closeModal() {
      modal.style.display = 'none'; // Ẩn modal
    }

    // Thêm sự kiện nhấn nút "Add new" để mở modal
    addRoomBtn.addEventListener('click', (e) => {
      e.preventDefault(); // Ngăn chặn hành vi mặc định (chuyển hướng)
      openModal();
    });

    // Thêm sự kiện nhấn nút đóng modal
    closeModalBtn.addEventListener('click', closeModal);

    // Đóng modal khi click bên ngoài nội dung modal
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        closeModal();
      }
    });

  </script>
<!-- Sử dụng sự kiện click để mở modal update Room-->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Lấy tất cả các nút "Sửa thông tin"
      const editButtons = document.querySelectorAll('.btn-edit-room');
      
      // Lặp qua từng nút và thêm sự kiện
      editButtons.forEach(button => {
          button.addEventListener('click', (e) => {
              e.preventDefault();

              // Lấy room_id từ thuộc tính data-room-id
              const roomId = button.getAttribute('data-room-id');

              // Hiển thị modal tương ứng
              const modal = document.getElementById(`modal-room-${roomId}`);
              if (modal) {
                  modal.style.display = 'flex';
              }
          });
      });

      // Đóng modal khi nhấn vào biểu tượng đóng
      const closeButtons = document.querySelectorAll('[data-close-modal]');
      closeButtons.forEach(button => {
          button.addEventListener('click', () => {
              const modal = button.closest('.modal-updateRoom');
              if (modal) {
                  modal.style.display = 'none';
              }
          });
      });

      // Đóng modal khi click bên ngoài nội dung modal
      const modals = document.querySelectorAll('.modal-updateRoom');
      modals.forEach(modal => {
          modal.addEventListener('click', (e) => {
              if (e.target === modal) {
                  modal.style.display = 'none';
              }
          });
      });
  });


  </script>
@endsection
