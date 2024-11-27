<!DOCTYPE html>
<!-- Coding by CodingNepal | www.codingnepalweb.com -->
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
    <link rel="icon" href="{{ url('favicon.ico')}} " type="image/x-icon">
    <link rel="stylesheet" href="{{ url('assets/css/admin/mylayout.css') }}">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


</head>
<body>
    <div class="sidebar">
        <div class="logo-details">
            <!-- <i class='bx bxl-dropbox' ></i> -->
            <img src="{{ url('assets/images/logo-app-2.png') }}" alt="">
            <a href="{{ route('admin.home.chat', $room_id) }}" class="logo_name">SpaceBox</a>
        </div>
        <ul class="nav-links">
            
            <li>
                <a href="{{ route('admin.getListUser') }}" class="{{ request()->routeIs('admin.getListUser') ||  url()->current() == url('/admin') ? 'active' : '' }}">
                    <i class='bx bx-user' ></i>
                    <span class="links_name">Quản Lý Người Dùng</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.getListRoom') }}" class="{{ request()->routeIs('admin.getListRoom') ? 'active' : ''}}" >
                    <i class='bx bxs-group'></i>
                    <span class="links_name">Quản Lý Room</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.getProfile')}} "  class="{{ request()->routeIs('admin.getProfile') ? 'active' : ''}}">
                    <i class='bx bx-id-card'></i>
                    <span class="links_name">Trang cá nhân</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.changePassForm')}} "  class="{{ request()->routeIs('admin.changePassForm') ? 'active' : ''}}">
                    <i class='bx bxs-pencil'></i>
                    <span class="links_name">Đổi mật khẩu</span>
                </a>
            </li>

            <li>
                <a href="#">
                    <i class='bx bx-cog' ></i>
                    <span class="links_name">Setting</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.home.chat', $room_id) }}">
                    <i class='bx bx-home' ></i>
                    <span class="links_name">Spacebox Chat</span>
                </a>
            </li>

            <li class="log_out">
                <a href="{{ route('account.logout') }}">
                    <i class='bx bx-log-out'></i>
                    <span class="links_name">Đăng xuất</span>
                </a>
            </li>
        </ul>
    </div>
    <section class="home-section">
        <nav>
            <div class="sidebar-button">
                <i class='bx bx-menu sidebarBtn'></i>
                <span class="managers">
                    Admin
                </span>
            </div>
            <div class="search-box">
                <input type="text" placeholder="Search...">
                <i class='bx bx-search' ></i>
            </div>
            <div class="profile-details">
                @if (Auth::check())
                    <span class="admin_name">{{ Auth::user()->username }}</span>
                    <img src="{{ Auth::user()->img_path }}" alt="">
                @endif
            </div>
        </nav>

        <div class="home-content">
            @yield('content')
        </div>
       
    </section>



    <script>
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".sidebarBtn");
        sidebarBtn.onclick = function() {
            sidebar.classList.toggle("active");
            if(sidebar.classList.contains("active")){
                sidebarBtn.classList.replace("bx-menu" ,"bx-menu-alt-right");
            }else{

                sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
            }
        }
    </script>

    
    @yield('scripts')
</body>
</html>