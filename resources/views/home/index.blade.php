<x-my-layout>
    <x-slot name="linkcss">
        <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    </x-slot>

    @include('components.header')
    <div class="home">
        <div class="landing-page flex-row">
            <div class="ldp-content flex-col">
                <H1>Home</H1>
            </div>
            <div class="ldp-sample">
                <img src="" alt="">
            </div>
        </div>
    </div>
    @include('components.footer')
</x-my-layout>