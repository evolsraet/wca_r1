@php
    $config = [
        'appName' => config('app.name'),
        'locale' => $locale = app()->getLocale(),
        'locales' => config('app.locales'),
    ];
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <script>
        window.Laravel = {
            appName: "{{ config('app.name', 'Laravel') }}"
        };
    </script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <script>
        window.config = @json($config);
    </script>
    <!-- Scripts -->
    @vite(['resources/v1/sass/app.scss', 'resources/v1/js/app.js'], 'build/v1')
</head>
<body class="font-sans antialiased" id="app">
    <router-view></router-view>
</body>
</html>