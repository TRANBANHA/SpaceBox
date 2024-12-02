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
                    <input type="hidden" id="roleCurrent" value="{{ Auth::user()->role_id }}">
                    <input type="hidden" id="userCurrent" value="{{ Auth::user()->user_id }}">
                    <img src="{{ Auth::user()->img_path }}" alt="">
                </div>
                <nav class="nav_menu">
                    <a href="#"><i class='bx bx-message-rounded-dots' ></i></a>
                    @if (Auth::user()->role_id != 1)
                        <div class="setting">
                            <a id="btn-setting"  href="#"><i class='bx bx-cog' ></i></a> 
                            <div id="option-setting" class="option-setting">
                                <ul>
                                    <button class="btn-option-setting btn_setting-inf" id="btn_setting-inf">Thông tin tài khoản</button>
                                    <button class="btn-option-setting btn_setting-pass" id="btn_setting-pass">Đổi mật khẩu</button>
                                </ul>
                            </div>

                            <div class="form-setting-container" id="form-setting-inf">
                                <h3>Thông tin tài khoản</h3>
                                <form class="profiles" action="{{ route('spacebox.updateProfile') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')

                                    <div class="setting-avt-name">
                                        <div class="img-avt">
                                            <img id="profileImage"  src="{{ $user->img_path }}" alt="">
                                            
                                            <input type="file" name="fileImg" id="avatarInput" style="display: none;" onchange="previewImage(event)">
                                            <button class="btn-add-avt" type="button" onclick="document.getElementById('avatarInput').click();">
                                                <i class='bx bxs-camera'></i>                   
                                            </button>
                                        </div>                
                                        <input class="name-user" type="text" name="username" value="{{ $user->username }}" required>

                                    </div>

                                    <div class="setting-inf">
                                        <div class="setting-gender">
                                            <span>Giới tính:</span>
                                            <div class="box-gd flex-row">
                                            <input id="gender_male" name="gender" type="radio" value="1" {{ $user->gender ? 'checked' : '' }}>
                                            <label for="gender_male">Nam</label>
                                            </div>
                                            <div class="box-gd flex-row">
                                            <input id="gender_female" name="gender" type="radio" value="0" {{ $user->gender ? '' : 'checked' }}>
                                            <label for="gender_female">Nữ</label>
                                            </div>
                                        </div>
                                        <div class="inp_text email">
                                            <span>Email:</span>
                                            <input class="inp_email" type="text" name="email" placeholder="{{ $user->email }}">
                                        </div>
                                        <div class="inp_text note">
                                            <span>Note:</span>
                                            <textarea class="inp_note" name="description" cols="40" rows="4" >{{ $user->description }}</textarea>
                                        </div>  
                                        
                                    </div>                         
                            
                                    <div class="buttons">
                                        <button type="reset" class="cancel-btn" id="cancel-setting-inf-btn">Hủy</button>
                                        <button type="submit" class="update-btn" >Cập nhật</button>
                                    </div>
                                    
                                </form>  

                            </div>
                            <div class="form-setting-container {{ $errors->any() || session('success') ? 'show' : '' }}" id="form-setting-pass">
                                <h3>Cập nhật mật khẩu</h3>
                                
                                <form class="profiles" action="{{ route('spacebox.updatePass') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="setting-pass">
                                        <div class="inp_text passOld">
                                            <span>Mật khẩu cũ:</span>
                                            <input class="input_pass passOld" type="text" name="passwordOld">
                                            @error('passwordOld')
                                                <small id="passwordOldError" class="auth-error" style="color: red">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="inp_text passNew">
                                            <span>Mật khẩu mới:</span>
                                            <input class="input_pass passnew" type="text" name="password">
                                        </div>
                                        <div class="inp_text passConfi">
                                            <span>Nhập lại mật khẩu:</span>
                                            <input class="input_pass passOld" type="text" name="password_confirmation">
                                            @error('password')
                                                <small id="password_confirmationError" class="auth-error" style="color:red;">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        @if(session('success'))
                                            <span id="notification-success" class="auth-notification" style="color: green;text-align: center;display: block;">Đổi mật khẩu thành công</span>
                                        @endif
                                    </div>
                                    <div class="buttons">
                                        <button type="reset" class="cancel-btn" id="cancel-setting-pass-btn">Hủy</button>
                                        <button type="submit" class="update-btn" >Cập nhật</button>
                                    </div>
                                </form>
                            </div>
                            
                        </div>
                    
                    @else
                        <div class="setting" style="display: none;">
                            <a id="btn-setting"  href="#"><i class='bx bx-cog' ></i></a> 
                        </div>
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
                <ul id="room-list">
                    @if ($rooms)

                        @foreach ($rooms as $room_i)
                            <a href="{{ route(Auth::user()->role_id == 1 ? 'admin.home.chat' : 'spacebox.home.chat', $room_i->room_id) }}" 
                                id="room-{{ $room_i->room_id }}" 
                                class="chat-item {{ $room_i->room_id == $room_id ? 'selected-group' : '' }}" >
                           
                                
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
                    @if($roomFirst)
                    <div class="user-info">
                        
                        <img src="{{ $roomFirst->avt_path }}" alt="User Avatar" class="user-avatar">
                        <div>
                            <span class="username">{{ $roomFirst->room_name }}</span>
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
                    @else
                        <p style="width: 100%;text-align: center;color: #ccc;">Không có phòng chat nào</p>
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
                                    @if(!$message->file_path)
                                        <p style="{{ $message->is_deleted ? 'font-style: italic;' : '' }}" >
                                            {{ $message->content }}
                                            <br>
                                            <span>{{ $message->created_at->format('H:i') }}</span>
                                        </p>
                                    @else
                                        @if(preg_match('/\.(jpg|jpeg|png|gif)$/i', $message->file_path))
                                            <!-- Nếu là ảnh, sử dụng thẻ <img> để hiển thị -->
                                            <a href="{{ $message->file_path }}" target="_blank">
                                                <img src="{{ $message->file_path }}" alt="Image" style="width: auto; max-height: 250px;" />
                                            </a>
                                        @elseif(preg_match('/\.(pdf)$/i', $message->file_path))
                                            <!-- Nếu là ảnh, sử dụng thẻ <img> để hiển thị -->
                                            
                                            <a href="{{ $message->file_path }}" target="_blank" class="file-mess-link">
                                                <i class='bx bx-file'></i>
                                                <p>{{ $message->content }}</p>
                                            </a>
                                        @else
                                            <!-- Các loại file khác, có thể chỉ hiển thị link tải về -->
                                            <a href="{{ $message->file_path }}" download="{{ $message->content }}" class="file-mess-link">
                                                <i class='bx bx-file'></i>
                                                <p>{{ $message->content }}</p>
                                            </a>
                                        @endif
                                        <!-- <a href="{{ $message->file_path }}" target="_blank">Truy cập</a> -->
                                        
                                        <span>{{ $message->created_at->format('H:i') }}</span>
                                    @endif
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
                        
                    </div>  
                    <form id="fileForm" action="{{ Auth::user()->role_id == 1 ? route('admin.chat.sendFile') : route('spacebox.chat.sendFile') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input  type="hidden" name="user_id" value="{{ Auth::user()->user_id }}">
                        <input  type="hidden" name="room_id" value="{{ $room_id ?? 0 }}">
                        <input type="file" name="file_mess" id="fileInput" style="display: none;" onchange="handleFileChange(event)"/>
                        <!-- Button -->
                        <button class="icon-btn" type="button" onclick="document.getElementById('fileInput').click()">
                            <i class='bx bxs-file-image'></i>
                        </button>
                    </form>

                   
                    <form class="formSendMess" action="{{ Auth::user()->role_id == 1 ? route('admin.chat.sendMess') : route('spacebox.chat.sendMess') }}" method="POST">
                        
                        @csrf

                        <input id="user_id" type="hidden" name="user_id" value="{{ Auth::user()->user_id }}">
                        <input id="room_id" type="hidden" name="room_id" value="{{ $room_id ?? 0 }}">

                        <input name="content" type="text" class="input-box" placeholder="Aa" required>
                        <div class="right-icons">
                            <button class="icon-btn" type="submit"><i class='bx bxs-send'></i></button>               
                        </div>
                    </form>
                   
            </footer>
            
        </section>

        <!-- Directory bên phải -->
        <aside class="directory" id="directory">
            <div class="community-details">
                    <!-- <h1>Thông tin nhóm</h1> -->
                <div class="profile-section">
                    <div class="img">
                        <img src="{{ $roomFirst->avt_path }}" alt="Logo">
                    </div>
                    <h2>{{ $roomFirst->room_name }}</h2>
                    <div class="member-online">
                        <p>{{ count($userInRooms) }} Members</p>
                        <p><span>Online</span></p>
                    </div>
                    <div class="actions">
                        <button class="action-btn">
                            <i class='bx bxs-bell-ring'></i>
                            <span>Im lặng</span>
                        </button>

                        <button class="action-btn">
                            <i class='bx bx-log-in' ></i>
                            <span>Rời nhóm</span>
                        </button>
                        <div class="setting_room">
                            @if ($roomFirst->created_by == Auth::user()->user_id) 
                                <button class="action-btn" id="action-btn-room">
                                    <i class='bx bxs-cog'></i>
                                    <span>Setting</span>
                                </button>
                            @endif
                            <div class="form-setting_room-container" id="form-setting-room">
                                <h3>Thông tin room</h3>
                                <input type="hidden" id="created_by" value="{{ $roomFirst->created_by }}">                                      
                                <form class="profile_room" action="{{ Auth::user()->role_id == 1 ? route('admin.userUpdateRoom') : route('spacebox.userUpdateRoom') }}"  method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')

                                    <input class="room_id"  type="text" name="room_id" value="{{ $roomFirst->room_id }}" style="display:none;">
                                    <div class="setting-avt-nameroom">
                                        <div class="img-avt-room">
                                            <img id="Room_Image"  src="{{ $roomFirst->avt_path }}" alt="">
                                            
                                            <input type="file" name="fileImg_room" id="avatar_Room_Input" style="display: none;" onchange="previewImageRoom(event)">
                                            <button class="btn-add-avt-room" type="button" onclick="document.getElementById('avatar_Room_Input').click();">
                                                <i class='bx bxs-camera'></i>                   
                                            </button>
                                        </div>
                                    </div>
                                    <input class="input-name-group" type="text" name="room_name" value="{{ $roomFirst->room_name }}" required>
                                    <div class="buttons">
                                        <button type="reset" class="cancel-btn" id="cancel-setting-room-btn">Hủy</button>
                                        <button type="submit" class="update-btn">Cập Nhật</button>
                                    </div>
                                </form>  
                            </div>   
                        </div>
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
        @endif
    </div>
</x-my-layout>

<!-- Event Realtime -->
@vite('resources/js/addroom.js')

@vite('resources/js/chat.js')
@vite('resources/js/room.js')



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        // event.stopPropagation();
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

    //MỞ OPTION SETTING
    const roleCurrentPage = document.getElementById('roleCurrent').value;
    if(roleCurrentPage != 1){
        document.getElementById('btn-setting').addEventListener('click', function() {
        // event.preventDefault(); 

        const optionSetting = document.getElementById('option-setting');
        
        optionSetting.classList.toggle('show'); 
        // event.stopPropagation();
        });

        // Đóng menu khi click ra ngoài
        document.addEventListener('click', function() {
            var optionSetting = document.getElementById('option-setting');

            if (!event.target.closest('#btn-setting') && !event.target.closest('#option-setting')) {
                optionSetting.classList.remove('show'); 
            }
        });

        // MỞ FORM SETTING INF
        document.getElementById('btn_setting-inf').addEventListener('click', function () {
            const form_setting_inf = document.getElementById('form-setting-inf');
            const chat_app = document.getElementById('chat-app');
            const optionSetting = document.getElementById('option-setting');

            optionSetting.classList.remove('show'); 
            chat_app.classList.toggle('hidden');
            form_setting_inf.classList.toggle('show');
            // event.stopPropagation();
        });

        document.getElementById('cancel-setting-inf-btn').addEventListener('click', function () {
            const form_setting_inf = document.getElementById('form-setting-inf');
            const chat_app = document.getElementById('chat-app');
            if (form_setting_inf.classList.contains('show')) {
                form_setting_inf.classList.remove('show');
            }
            if (chat_app.classList.contains('hidden')) {
                chat_app.classList.remove('hidden');
            }
        });

        document.addEventListener('DOMContentLoaded',function(){
            const chat_app = document.getElementById('chat-app');
            const form_setting_pass = document.getElementById('form-setting-pass');

            if (form_setting_pass.classList.contains('show')) {
                chat_app.classList.add('hidden');
            }

            if ($('#passwordOldError').length) {
                $('#passwordOldError').delay(3000).fadeOut();
            }
            if ($('#password_confirmationError').length) {
                $('#password_confirmationError').delay(3000).fadeOut();
            }
            if ($('#notification-success').length) {
                $('#notification-success').delay(3000).fadeOut();
            }
        })

        //MỞ FORM UPDATE PASSWORD
        document.getElementById('btn_setting-pass').addEventListener('click',function(){
            const chat_app = document.getElementById('chat-app');
            const form_setting_pass = document.getElementById('form-setting-pass');
            const optionSetting = document.getElementById('option-setting');

            optionSetting.classList.remove('show'); 
            chat_app.classList.toggle('hidden');
            form_setting_pass.classList.toggle('show');
            // event.stopPropagation();
        });

        document.getElementById('cancel-setting-pass-btn').addEventListener('click', function () {
            const form_setting_pass = document.getElementById('form-setting-pass');
            const chat_app = document.getElementById('chat-app');
            if (form_setting_pass.classList.contains('show')) {
                form_setting_pass.classList.remove('show');
            }
            if (chat_app.classList.contains('hidden')) {
                chat_app.classList.remove('hidden');
            }
        });

        function previewImage(event) {
          var reader = new FileReader();
          reader.onload = function() {
            var output = document.getElementById('profileImage');
            output.src = reader.result;
          }
          reader.readAsDataURL(event.target.files[0]);
        };
    }


   


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

    
    function handleFileChange(event) {
        const file = event.target.files[0]; // Lấy file đầu tiên
        if (file) {
            console.log('File được chọn:', file.name);
            
            // Sau khi chọn file, tự động submit form
            document.getElementById('fileForm').submit();
        } else {
            console.log('Chưa chọn file');
        }
    }

    

    
    document.getElementById('toggleDirectory').addEventListener('click', function () {
        // event.preventDefault(); 
        const directory = document.getElementById('directory');
        const directory_resources = document.getElementById('directory_resources');
        if(directory_resources.classList.contains('show')){
            directory_resources.classList.remove('show');
        }else {
            directory.classList.toggle('show');
        }
    });

    document.getElementById('btn_resources').addEventListener('click', function () {
        // event.preventDefault(); 
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

    const userCurrentRoom = document.getElementById('userCurrent').value;
    const created_by =document.getElementById('created_by').value;

    if(created_by == userCurrentRoom){
        function previewImageRoom(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('Room_Image');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);  
        };
        document.getElementById('action-btn-room').addEventListener('click', function () {
            const form_setting_room = document.getElementById('form-setting-room');
            const chat_app = document.getElementById('chat-app');
            chat_app.classList.toggle('hidden');
            form_setting_room.classList.toggle('show');
            event.stopPropagation();
        });

        document.getElementById('cancel-setting-room-btn').addEventListener('click', function () {
            const form_setting_room = document.getElementById('form-setting-room');
            const chat_app = document.getElementById('chat-app');
        if (form_setting_room.classList.contains('show')) {
            form_setting_room.classList.remove('show');
        }
        if (chat_app.classList.contains('hidden')) {
            chat_app.classList.remove('hidden');
        }
        });
    }   
    
</script>



