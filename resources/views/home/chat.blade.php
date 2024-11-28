<x-my-layout>
    <x-slot name="linkcss">
        <link rel="stylesheet" href="{{ asset('assets/css/chat.css') }}">
        <!-- animation library -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    </x-slot>
    <div class="chat-app" id="chat-app">
        <!-- Sidebar trái -->
        <aside class="sidebar">
            <div class="menu">
                <div class="my_avt">
                    <img src="{{ Auth::user()->img_path }}" alt="">
                </div>
                <nav class="nav_menu">
                    <a href="#"><i class='bx bx-message-rounded-dots' ></i></a>
                    <a href="#"><i class='bx bxs-user-rectangle'></i></a>
                    <a href="#"><i class='bx bx-cog' ></i></a>   
                    @if (Auth::user()->role_id == 1)
                        <a href="{{ route('admin.index') }}"><i class='bx bxs-user-account'></i></a>
                    
                    @endif
                </nav>
            </div>
           
            <nav class="logout">
                <a href="{{ route('account.logout') }}"><i class='bx bx-log-out'></i></a>
            </nav>
        </aside>

        <!-- Danh sách tin nhắn -->
        <section class="message-list">
            <header style="margin-top: 10px">
                <h2>SpaceBox</h2>
                <div class="header-group">
                    <input type="text" placeholder="Search on SpaceBox" class="search-input">
                    <div class="add-group">
                        <button class="btn-add-group" id="btn-add-group"><i class='bx bx-group'></i></button>


                        <div class="form-container" id="form-add">
                            <h3>Tạo nhóm</h3>

                            <form method="POST" action="{{ Auth::user()->role_id == 1 ? route('admin.chat.addroom') : route('spacebox.chat.addroom') }}" >
                                @csrf

                                <div class="add-inf">
                                    <div class="img-group">
                                        <img src="https://res.cloudinary.com/dy6y1gpgm/image/upload/v1732224761/icon_group_leejca.png" alt="">
                                        <button class="btn-add-imggroup"><i class='bx bxs-camera'></i></button>
                                    </div>
                                  <input class="name-group" type="text" name="room_name" placeholder="Nhập tên nhóm..." required>
                                </div>
                                <div class="find">
                                    <h4>Chọn thành viên:</h4>
                                    <div class="find-name">
                                        <input class="input-find-name" type="text" name="group_name" placeholder="Tìm kiếm">
                                        <button class="btn_find-name"><i class='bx bx-search-alt' ></i></button>
                                    </div>                                  
                                </div>
                                
                               
                                    <div class="list">
                                        @foreach ($listUsers as $user)
                                            <label><input type="checkbox" name="members[]" value="{{ $user->user_id }}">{{ $user->username }}</label>
                                        @endforeach
                                    </div>
                        
                              <div class="buttons">
                                <button type="reset" class="cancel-btn" id="cancel-btn">Hủy</button>
                                <button type="submit" class="create-btn">Tạo nhóm</button>
                              </div>
                            </form>  

                        </div>
                    </div>
                    
                </div>
                
            </header>
            <div class="chat-list">
                <ul>
                    @if ($rooms)
                        @foreach ($rooms as $room_i)
                            <a href="{{ route(Auth::user()->role_id == 1 ? 'admin.home.chat' : 'spacebox.home.chat', $room_i->room_id) }}" 
                                class="chat-item" 
                                id="room_{{ $room_i->room_id }}" 
                                data-room-id="{{ $room_i->room_id }}" 
                                onclick="selectRoom(event, {{ $room_i->room_id }})">
                           
                                
                                <img src="{{ $room_i->avt_path }}" alt="Logo" class="avatar">
                                <div class="chat-info">
                                    <h4>{{ $room_i->room_name }}</h4>
                                    <!-- Hiển thị tin nhắn mới nhất và thời gian -->
                                        <p style="font-weight: bold;">{{ $room_i->latestMess }}</p>
                                </div>
                                <div class="chat-meta">
                                    <span class="time">{{ $room_i->latestMessTime->format('H:i') }}</span>
                                    <!-- <span class="unread">3</span> -->
                                </div>
                            </a>
                        @endforeach
                    @endif
                    <!-- Add more items as needed -->
                </ul>
            </div>
        </section>

        <!-- Chi tiết tin nhắn và người dùng -->
        <section class="chat-container">
            <header class="chat-header">
                <div class="user-info">
                    <img src="{{ Auth::user()->img_path }}" alt="User Avatar" class="user-avatar">
                    <div>
                        <span class="username">{{ Auth::user()->username }}</span>
                        <p class="status">Online</p>
                    </div>
                </div>
                @if ($messages)
                    <div class="call-Information">
                        <a href="#"><i class='bx bxs-phone-call'></i></a>
                        <a href="#"><i class='bx bxs-video'></i></a>
                        <a href="#" id="toggleDirectory" ><i class='bx bxs-error-circle'></i></a>
                    </div>         
                @endif  
            </header>

            

            @if ($messages)
            <div class="chat-content">
                <div class="box-mess-ghim">
                    @php
                        $pinMessNews = $messages;
                        $pinMessNews = $messages->sortByDesc('updated_at');
                    @endphp
                    @foreach ($pinMessNews as $message)
                        @if ($message->is_pinned && $message->is_deleted == 0)
                            <div class="message-ghim">
                                <button class="ghim"><i class='bx bxs-pin'></i></button>              
                                <p>{{ $message->content }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>
                
                
                
                <div class="message-chat">
                    @foreach ($messages as $message)
                        
                        <div class="message {{ $message->is_current_user ? 'user' : 'friend' }}">
                            <img src="{{ $message->is_current_user ? Auth::user()->img_path : $message->img_path }}" alt="Avatar" class="avatar">

                            <div class="name_bubble">
                                @if (!$message->is_current_user)
                                    <div class="name">
                                        <span>{{ $message->username }}</span>
                                    </div>
                                @endif
                                <div class="bubble">
                                    <p style={{ $message->is_deleted == 1 ?? "font-style: italic"  }}>{{ $message->is_deleted == 0 ? $message->content : 'Tin nhắn đã được thu hồi'  }}<br><span>{{ $message->created_at->format('H:i') }}</span></p> <!-- Hiển thị nội dung và giờ -->
                                </div>
                            </div>

                            @if ($message->is_deleted == 0)
                                <div class="other">
                                        <!-- Các hành động chỉ hiển thị cho người dùng hiện tại -->
                                    <button class="btn_other"><i class='bx bx-dots-vertical-rounded'></i></button>
                                    <div class="popup-menu {{$message->is_current_user ? 'user' : 'friend'}}">
                                        <ul>
                                            @foreach ($userInRooms as $userInRoom)
                                                @if (Auth::user()->user_id == $userInRoom->user_id && $userInRoom->role_id == 2)
                                                    <li>
                                                        <form action="{{ route(Auth::user()->role_id == 1 ? 'admin.chat.deleteMess':'spacebox.chat.deleteMess', $message->message_id) }}" method="post">
                                                            @csrf
                                                            @METHOD('DELETE')
                                                            <button type="submit">Thu hồi</button>

                                                        </form>
                                                    </li>
                                                @endif
                                            @endforeach
                                            <li>
                                                <form action="#">
                                                    <button type="submit">Chuyển tiếp</button>
                                                </form>
                                            </li>
                                            <li>
                                                @if ($message->is_deleted == 0)
                                                    @if ($message->is_pinned)
                                                        <form action="{{ route(Auth::user()->role_id == 1 ? 'admin.chat.unpinMess':'spacebox.chat.unpinMess', $message->message_id) }}" method="POST">
                                                            @csrf
                                                            @METHOD('PATCH')
                                                            <button type="submit">Bỏ ghim</button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route(Auth::user()->role_id == 1 ? 'admin.chat.pinMess':'spacebox.chat.pinMess', $message->message_id) }}" method="POST">
                                                            @csrf
                                                            @METHOD('PATCH')
                                                            <button type="submit">Ghim</button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
               
            </div>
            <footer class="chat-footer">
                    <div class="left-icons">
                      <button class="icon-btn" id="btn_plus"><i class='bx bx-plus-circle'></i></button>
                        <div class="record" id="record">
                            <i class='bx bxs-microphone'></i>
                            <p>gửi tệp ghi âm</p>
                        </div>
                        <input type="file" id="fileInput" style="display: none;" />
                        <!-- Button -->
                        <button class="icon-btn" onclick="document.getElementById('fileInput').click()">
                            <i class='bx bxs-file-image'></i>
                        </button>
                    </div>
                    <form class="formSendMess" action="{{ Auth::user()->role_id == 1 ? route('admin.chat.sendMess') : route('spacebox.chat.sendMess') }}" method="POST">
                        
                        @csrf

                        <input id="user_id" type="hidden" name="user_id" value="{{ Auth::user()->user_id }}">
                        <input id="room_id" type="hidden" name="room_id" value="{{ $room_id ?? 0 }}">

                        <input name="content" type="text" class="input-box" placeholder="Aa">
                        <div class="right-icons">
                            <button class="icon-btn" type="submit"><i class='bx bxs-send'></i></button>               
                        </div>
                    </form>
                   
            </footer>
            @endif
        </section>

        <!-- Directory bên phải -->
        <aside class="directory" id="directory">
            <div class="community-details">
                    <h1>Details Community</h1>
                <div class="profile-section">
                    <div class="img">
                        <img src="{{ url('assets/images/male.png') }}" alt="Logo">
                    </div>
                    <h2>Dribbble & Behance...</h2>
                    <div class="member-online">
                        <p>1232 Members</p>
                        <p><span></span>200 Online</p>
                    
                    </div>
                    <div class="actions">
                        <button class="action-btn">
                            <i class='bx bxs-bell-ring'></i>
                            <span>Unmute</span>
                        </button>

                        <button class="action-btn">
                            <i class='bx bx-log-in' ></i>
                            <span>Leave</span>
                        </button>
                        
                    </div>
                </div>
                <ul class="menu">
                    <li >
                        <i class='bx bxs-image' style="color: blue"></i>
                        <button id="btn_resources" class="menu-btn">Docs, Link, Media <span class="badge">230</span></button>
                    </li>
                    <li>
                        <i class='bx bxs-pin' style="color: yellow"></i>
                        <button class="menu-btn">Star Message <span class="badge empty">Empty</span></button>
                    </li>

                </ul>
                <div class="members-section">
                    <div class="members-section_top">
                        <h3>1232 Members</h3>
                        <button class="action-btn-search">
                            <i class='bx bx-search-alt' ></i>
                        </button>
                    </div>
                    <div class="members-section_buttom">
                        <a href="#"  id="add-members" ><i id='icon_add' class='bx bxs-user-plus'></i>Add Members</a>
                        <a></a>
                        {{-- <div class="search-bar">
                            <input type="text" placeholder="Search members..." />
                        </div> --}}
                        <ul class="members-list">
                            @foreach ($userInRooms as $userInRoom)
                                <li class="member">
                                    <div class="member-right">
                                        <img src="{{ $userInRoom->img_path }}" alt="User 1" class="member-avatar">
                                        <div class="member-info">
                                            <h4>{{ $userInRoom->username }}</h4>
                                            @if($userInRoom == Auth::user())
                                                <p>You</p>
                                            @else
                                                <p>{{ $userInRoom->email }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <span style="color: {{ ['red', 'aqua'][$userInRoom->role_id - 2] ?? 'Unknown' }}">{{ ['Moderate', 'Normal'][$userInRoom->role_id - 2] ?? 'Unknown' }}</span>
                                    
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
            
        </aside>
        <aside class="directory_resources" id="directory_resources">
            <button class="btn-back_resources">
                <i class='bx bx-arrow-back'></i>
                <h1>Media Docs</h1>
            </button>
            <div class="container-resources">
                <div class="resources media">
                    <div class="header">
                        <button class="btn_resources" id="btm_media">Media</button>
                        <button class="btn_resources" id="btm_file">File</button>
                        <button class="btn_resources" id="btm_link">Link</button>
                    </div>
                    <div class="container-media">
                        <div class="media-content">
                            <img src="{{ url('assets/images/male.png') }}" alt="">
                        </div>
                        <div class="media-content">
                            <img src="{{ url('assets/images/male.png') }}" alt="">
                        </div>
                        <div class="media-content">
                            <img src="{{ url('assets/images/male.png') }}" alt="">
                        </div>
                        <div class="media-content">
                            <img src="{{ url('assets/images/male.png') }}" alt="">
                        </div>
                        <div class="media-content">
                            <img src="{{ url('assets/images/male.png') }}" alt="">
                        </div>                        <div class="media-content">
                            <img src="{{ url('assets/images/male.png') }}" alt="">
                        </div>
                        <div class="media-content">
                            <img src="{{ url('assets/images/male.png') }}" alt="">
                        </div>
                        <div class="media-content">
                            <img src="{{ url('assets/images/male.png') }}" alt="">
                        </div>
                        <div class="media-content">
                            <img src="{{ url('assets/images/male.png') }}" alt="">
                        </div>
                        <div class="media-content">
                            <img src="{{ url('assets/images/male.png') }}" alt="">
                        </div>
                        <div class="media-content">
                            <img src="{{ url('assets/images/male.png') }}" alt="">
                        </div>
                        <div class="media-content">
                            <img src="{{ url('assets/images/male.png') }}" alt="">
                        </div>
                        <div class="media-content">
                            <img src="{{ url('assets/images/male.png') }}" alt="">
                        </div>
                        <div class="media-content">
                            <img src="{{ url('assets/images/male.png') }}" alt="">
                        </div>
                        <div class="media-content">
                            <img src="{{ url('assets/images/male.png') }}" alt="">
                        </div>
                    </div>
                    
                </div>
                
                <div class="resources file" style="display: none;">
                    <div class="header">
                        <button class="btn_resources" id="btf_media">Media</button>
                        <button class="btn_resources" id="btf_file">File</button>
                        <button class="btn_resources" id="btf_link">Link</button>
                    </div>
                    <div class="container-file">
                        <div class="file-content">
                            <img src="{{ url('assets/images/male.png') }}" alt="">

                        </div>
                    </div>
                    <!-- Nội dung File ở đây -->
                </div>

                <div class="resources link" style="display: none;">
                    <div class="header">
                        <button class="btn_resources" id="btl_media">Media</button>
                        <button class="btn_resources" id="btl_file">File</button>
                        <button class="btn_resources" id="btl_link">Link</button>
                    </div>
                    <p>llllllllllll</p>
                    <!-- Nội dung File ở đây -->
                </div>

            </div>

        </aside>
        
    </div>
</x-my-layout>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@vite('resources/js/app.js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Laravel Echo:', window.Echo);
    });
</script>
<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        const messageContainer = document.querySelector('.chat-content');
        messageContainer.scrollTop = messageContainer.scrollHeight;
    });
</script>
<script>
    // FORM ADD GROUP
     document.getElementById('btn-add-group').addEventListener('click', function () {
        const form_add = document.getElementById('form-add');
        const chat_app = document.getElementById('chat-app');
        chat_app.classList.toggle('hidden');
        form_add.classList.toggle('show');
        event.stopPropagation();
    });
    document.getElementById('cancel-btn').addEventListener('click', function () {
        const form_add = document.getElementById('form-add');
        const chat_app = document.getElementById('chat-app');
        if (form_add.classList.contains('show')) {
            form_add.classList.remove('show');
        }
        if (chat_app.classList.contains('hidden')) {
            chat_app.classList.remove('hidden');
        }

    });


    // PLUS
    document.getElementById('btn_plus').addEventListener('click', function () {
        const record = document.getElementById('record');
        record.classList.toggle('show');
        event.stopPropagation();
    });


    document.addEventListener('click', function () {
    const record = document.getElementById('record');
    if (record.classList.contains('show')) {
        record.classList.remove('show');
    }
    });
    document.getElementById('record').addEventListener('click', function () {
        alert('record đã được chọn!');
    });


    // ADD FILE
    document.getElementById('fileInput').addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            console.log('File được chọn:', file.name);
            // Thực hiện các hành động khác, ví dụ upload file
        }
    });

    
    document.getElementById('toggleDirectory').addEventListener('click', function (event) {
        event.preventDefault(); 
        const directory = document.getElementById('directory');
        const directory_resources = document.getElementById('directory_resources');
        if(directory_resources.classList.contains('show')){
            directory_resources.classList.remove('show');
        }else {
            directory.classList.toggle('show');
        }
    });

    document.getElementById('btn_resources').addEventListener('click', function (event) {
        event.preventDefault(); 
        const directory_resources = document.getElementById('directory_resources');
        const directory = document.getElementById('directory');

        directory_resources.classList.add('show', 'animate__animated', 'animate__fadeInRight');
        directory.classList.remove('show');
        

    });

    document.querySelector('.btn-back_resources').addEventListener('click', function() {
     const directory_resources = document.getElementById('directory_resources');
     const directory = document.getElementById('directory');
     directory_resources.classList.remove('show');
     directory.classList.add('show');

    });




    document.getElementById('btm_media').addEventListener('click', function () {
        document.querySelector('.resources.media').style.display = 'block';
        document.querySelector('.resources.file').style.display = 'none';
        document.querySelector('.resources.link').style.display = 'none';
    });
    document.getElementById('btm_file').addEventListener('click', function () {
        document.querySelector('.resources.file').style.display = 'block';
        document.querySelector('.resources.media').style.display = 'none';
        document.querySelector('.resources.link').style.display = 'none';
    });
    document.getElementById('btm_link').addEventListener('click', function () {
        document.querySelector('.resources.file').style.display = 'none';
        document.querySelector('.resources.media').style.display = 'none';
        document.querySelector('.resources.link').style.display = 'block';
    });

    document.getElementById('btf_media').addEventListener('click', function () {
        document.querySelector('.resources.media').style.display = 'block';
        document.querySelector('.resources.file').style.display = 'none';
        document.querySelector('.resources.link').style.display = 'none';
    });
    document.getElementById('btf_file').addEventListener('click', function () {
        document.querySelector('.resources.file').style.display = 'block';
        document.querySelector('.resources.media').style.display = 'none';
        document.querySelector('.resources.link').style.display = 'none';
    });
    document.getElementById('btf_link').addEventListener('click', function () {
        document.querySelector('.resources.link').style.display = 'block';
        document.querySelector('.resources.media').style.display = 'none';
        document.querySelector('.resources.file').style.display = 'none';
    });
    document.getElementById('btl_media').addEventListener('click', function () {
        document.querySelector('.resources.media').style.display = 'block';
        document.querySelector('.resources.file').style.display = 'none';
        document.querySelector('.resources.link').style.display = 'none';
    });
    document.getElementById('btl_file').addEventListener('click', function () {
        document.querySelector('.resources.file').style.display = 'block';
        document.querySelector('.resources.media').style.display = 'none';
        document.querySelector('.resources.link').style.display = 'none';
    });
    document.getElementById('btl_link').addEventListener('click', function () {
        document.querySelector('.resources.file').style.display = 'none';
        document.querySelector('.resources.media').style.display = 'none';
        document.querySelector('.resources.link').style.display = 'block';
    });


    document.querySelectorAll('.btn_other').forEach(button => {
    button.addEventListener('click', function (e) {
        const popupMenu = this.nextElementSibling; // Popup ngay sau nút
        const isVisible = popupMenu.style.display === 'block';

        // Ẩn tất cả các popup khác trước khi mở popup mới
        document.querySelectorAll('.popup-menu').forEach(menu => menu.style.display = 'none');

        // Hiển thị/Ẩn popup
        popupMenu.style.display = isVisible ? 'none' : 'block';

        // Ngăn không cho sự kiện click lan ra ngoài
        e.stopPropagation();
        });
    });

    // Đóng popup khi click ra ngoài
    document.addEventListener('click', function () {
        document.querySelectorAll('.popup-menu').forEach(menu => menu.style.display = 'none');
    });

  
   
    
    document.addEventListener('DOMContentLoaded', function() {
        const selectedRoomId = localStorage.getItem('selectedRoomId'); // Lấy room_id đã lưu trong localStorage

        if (selectedRoomId) {
            // Nếu có room_id, tìm phần tử và thay đổi background
            const selectedRoom = document.getElementById('room_' + selectedRoomId);
            if (selectedRoom) {
                selectedRoom.classList.add('selected-group'); // Thêm class để thay đổi background
            }
        }
    });

    function selectRoom(event, roomId) {
        // Ngăn chặn hành động mặc định của thẻ <a> (không chuyển hướng ngay lập tức)
        event.preventDefault();

        // Lưu room_id vào localStorage để duy trì trạng thái khi load lại trang
        localStorage.setItem('selectedRoomId', roomId);

        // Xóa class 'selected-group' khỏi tất cả các nhóm
        const allRooms = document.querySelectorAll('.chat-item');
        allRooms.forEach(room => room.classList.remove('selected-group'));

        // Thêm class 'selected-group' vào nhóm được chọn
        const selectedRoom = document.getElementById('room_' + roomId);
        if (selectedRoom) {
            selectedRoom.classList.add('selected-group');
        }

        // Sau khi thay đổi background, thực hiện hành động chuyển hướng tới URL của phòng
        setTimeout(function() {
            window.location.href = selectedRoom.href;
        }, 0); // Thực hiện chuyển hướng sau một khoảng thời gian nhỏ (200ms)
    }
</script>


