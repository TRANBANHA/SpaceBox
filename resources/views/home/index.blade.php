<x-my-layout>
    <x-slot name="linkcss">
        <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    </x-slot>

    @include('components.header')
    <div class="home">
        <div class="landing-page flex-row">
            <div class="ldp-content flex-col">
                <h1>home</h1>
                @if(isset($username))
                    <span>Chào, {{ $username }}</span>
                    <a class="link-auth" href="{{ route('account.logout.auth') }}">Đăng xuất</a>
                @endif
            </div>
            <div class="ldp-sample">
                <img src="" alt="">
            </div>
        </div>
    </div>
    @include('components.footer')
</x-my-layout>