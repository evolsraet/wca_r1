<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', '관리자')</title>
    @vite(['resources/v2/sass/app.v2.scss', 'resources/v2/js/app.v2.js'], 'build/v2')
    <style>
        @media (min-width: 768px) {
            .admin-sidebar {
                position: sticky;
                top: 0;
                min-height: 100vh;
                width: 250px;
                border-right: 1px solid #dee2e6;
            }
        }
    </style>
</head>
<body class="bg-light">
    @include('v2.partials.common')

    <!-- 모바일용 사이드바 오프캔버스 -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="adminSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">관리자 메뉴</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            @include('v2.partials.admin.sidebar')
        </div>
    </div>

    <div class="d-flex admin-wrapper">
        <aside class="admin-sidebar d-none d-md-block bg-white">
            @include('v2.partials.admin.sidebar')
        </aside>

        <div class="flex-grow-1">
            {{-- @include('v2.partials.admin.header') --}}

            <main class="p-4">
                @yield('content')
            </main>

            @include('v2.partials.admin.footer')
        </div>
    </div>

</body>
</html>
