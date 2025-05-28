<nav class="navbar navbar-expand-lg sticky-top
  @auth
    {{ Auth::user()->hasRole('dealer') ? 'dealer-header' : 'default-header' }}
  @else
    default-header
  @endauth">
  <div class="container-fluid">
    <a class="navbar-brand logo-text" href="{{ route('home') }}">wecarlogo</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">

      {{-- 메뉴 영역 --}}
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        @auth
          @if(Auth::user()->hasRole('user'))
            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">내차조회</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">내 매물관리</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">이용후기</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">명의이전서류</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">공지사항</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->is('v2/introduce') ? 'active' : '' }}" href="{{ route('home') }}/introduce">서비스소개</a></li>
          @elseif(Auth::user()->hasRole('dealer'))
            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">입찰하기</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">공지사항</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">클레임</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}/introduce">서비스소개</a></li>
          @endif
        @else
          {{-- 게스트 메뉴 --}}
          <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">내차조회</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">이용후기</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->is('v2/introduce') ? 'active' : '' }}" href="{{ route('home') }}/introduce">서비스소개</a></li>
        @endauth
      </ul>

      {{-- 로그인/회원 메뉴 영역 --}}
      <ul class="navbar-nav">
        @auth
          <li class="nav-item dropdown">
            <a class="btn btn-danger dropdown-toggle user-dropdown-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              {{ Auth::user()->name }} 님
            </a>
            <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu">
              <li><a class="dropdown-item" href="#">내 정보 수정</a></li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button class="dropdown-item" type="submit">로그아웃</button>
                </form>
              </li>
            </ul>
          </li>
        @else
          <li class="nav-item">
            <a href="{{ route('login') }}" class="btn login-rounded-btn d-flex align-items-center">
              <div class="login-icon me-2"></div>
              <span>로그인</span>
            </a>
          </li>
        @endauth
      </ul>
    </div>
  </div>
</nav>