<aside class="bg-white border-end sticky-top shadow" style="width: 250px; min-height: 100vh;">


    <div class="d-flex flex-column h-100 bg-white border-end px-3 py-4">
        {{-- 유저정보 --}}
        <div class="text-center mb-4">
            <div class="rounded-circle bg-light mx-auto" style="width: 64px; height: 64px;">
                <img src="{{ asset('images/admin/profile.png') }}" class="rounded-circle mb-2" width="60">
            </div>
            <div class="fw-bold mt-2">데모관리자</div>
            <div class="text-muted small">admin@demo.com</div>
            <div class="d-flex justify-content-center gap-2 mt-2">
                <button class="btn btn-sm btn-outline-secondary" onclick="logout()">로그아웃</button>
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-gear"></i> 수정</button>
            </div>
        </div>
    
        {{-- 대시보드 --}}
        <div class="mb-3">
            <a href="{{ route('admin.index') }}" class="btn d-block text-white text-center fw-bold rounded bg-secondary">
                <div class="icon mb-1">
                    <i class="bi bi-grid-fill fs-4"></i>
                </div>
                대시보드
            </a>
        </div>
    
        {{-- 메뉴 목록 --}}
        <ul class="nav flex-column gap-2">
            <li class="nav-item">
                <a href="/admin/users" class="nav-link d-flex align-items-center text-muted">
                    <div class="icon me-2"><i class="bi bi-person"></i></div>
                    <span>회원 관리</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/payments" class="nav-link d-flex align-items-center text-muted">
                    <div class="icon me-2"><i class="bi bi-cash-coin"></i></div>
                    <span>입금 관리</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.auction.list') }}" class="nav-link d-flex align-items-center text-danger fw-bold">
                    <div class="icon me-2"><i class="bi bi-car-front-fill"></i></div>
                    <span>매물 관리</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/reviews" class="nav-link d-flex align-items-center text-muted">
                    <div class="icon me-2"><i class="bi bi-megaphone"></i></div>
                    <span>후기 관리</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/notices" class="nav-link d-flex align-items-center text-muted">
                    <div class="icon me-2"><i class="bi bi-pencil"></i></div>
                    <span>공지 관리</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/claims" class="nav-link d-flex align-items-center text-muted">
                    <div class="icon me-2"><i class="bi bi-stars"></i></div>
                    <span>클레임 관리</span>
                </a>
            </li>
        </ul>
    </div>

</aside>