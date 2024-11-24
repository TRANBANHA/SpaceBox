<x-my-layout>
    <x-slot name="linkcss">
        <link rel="stylesheet" href="{{ asset('assets/css/chat.css') }}">
    </x-slot>

   
    <div class="chat">
        <div class="landing-page flex-row">
            <div class="ldp-content flex-col">
                <h1>home</h1>
                @if (Auth::check())
                    <span>Chào {{ Auth::user()->username }}</span>
                    <a class="link-auth" href="{{ route('account.logout') }}">Đăng xuất</a>
                @endif
            </div>
            <div class="ldp-sample">
                <img src="" alt="">
            </div>
        </div>
    </div>
</x-my-layout>