
<section id="header">
    <div class="header flex-row">
        <h1 style="font-size: 18px;">Phòng chat miễn phí</h1>
        <div class="logo-web flex-row">
            <i class="fa-solid fa-users logo-i"></i>
            <a href="/spacebox" class="logo-home">SpaceBox</a>
        </div>
        <div class="action-auth flex-row">
            @if (Auth::check())
                <span>Chào {{ Auth::user()->username }}</span>
                <a class="link-auth" href="{{ route('account.logout') }}">Đăng xuất</a>
            @else
                <a class="link-auth" href="{{ route('account.login') }}">Đăng nhập</a>
                <a class="link-auth" href="{{ route('account.register') }}">Đăng ký</a>
            @endif
        </div>
    </div>
</section>