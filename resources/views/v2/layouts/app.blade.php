<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Scripts -->
    @vite(['resources/v2/sass/app.v2.scss'], 'build/v2')
    @vite(['resources/v2/js/app.v2.js'], 'build/v2')
    {{-- @php --}}
        {{-- $viteDev = app()->environment('local') ? 'http://localhost:5173' : mix(''); --}}
    {{-- @endphp --}}
    {{-- <script type="module" src="{{ $viteDev }}/resources/v2/js/app.v2.js" defer></script> --}}

</head>
<body>

    @include('v2.partials.header')

    <main>
        @if(isset($container) && $container === false)
            @yield('content')
        @else
            <div class="container py-5">
                @yield('content')
            </div>
        @endif
    </main>

    @include('v2.partials.footer')
    {{-- 히든 아이프레임 --}}
    <iframe id="hidden-iframe" name="hidden-iframe" style="display: none;"></iframe>
</body>
</html>
