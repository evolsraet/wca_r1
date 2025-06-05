@php
  $prefix = 'v2';

  if (Auth::check()) {
    $role = Auth::user()->hasRole('dealer') ? 'dealer' : 'user';
  } else {
    $role = 'guest';
  }

  $roleMenus = config('auction.menus.' . $role, []);
  $commonMenus = config('auction.menus.common', []);

  $menus = array_merge($roleMenus, $commonMenus)
@endphp
{{-- 기본 네비게이션 --}}
<nav class="navbar navbar-expand-lg sticky-top header-navbar {{ $role === 'dealer' ? 'dealer-header' : 'default-header' }}">
  <div class="container-fluid">
    <a class="navbar-brand logo-text" href="{{ route('home') }}">wecarlogo</a>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        @foreach ($menus as $key => $menu)
          @php
            $isActive = request()->is($prefix . '/' . $key . '*');
          @endphp

          <li class="nav-item">
            <a class="nav-link {{ $isActive ? 'active' : '' }}" href="{{ url('v2' . $menu['url']) }}">
              {{ $menu['label'] }}
            </a>
          </li>
        @endforeach
      </ul>

      <ul class="navbar-nav">
        @auth
        <li class="nav-item dropdown" x-data="{ dropdown: false }">
            <a class="btn btn-primary dropdown-toggle user-dropdown-btn" href="#" id="userDropdown1" role="button" data-bs-toggle="dropdown" aria-expanded="false" @click="dropdown = !dropdown">
              {{ Auth::user()->name }} 님
            </a>
            <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu" :class="{ 'show': dropdown }" aria-labelledby="userDropdown1" style="z-index: 2000;">
              <li><a class="dropdown-item" href="{{ route('modify') }}">내 정보 수정</a></li>
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

    <a class="navbar-toggler navbar-toggle-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDark" aria-controls="offcanvasDark">
      <i class="mdi mdi-menu"></i>
      {{-- <img src="{{ $role === 'dealer' ? asset('images/toggle-nav-wh.png') : asset('images/toggle-nav-black.png') }}" alt="메뉴" class="toggle-nav-img"> --}}
    </a>
  </div>
</nav>

{{-- 모바일 메뉴 --}}
<div class="offcanvas offcanvas-end text-bg-dark mobile-menu" tabindex="-1" id="offcanvasDark" aria-labelledby="offcanvasDarkLabel">
  <div class="offcanvas-header {{ $role === 'dealer' ? 'isUser' : '' }}">
    <div class="row col-12">
      <div class="col-9">
        @auth
        <div class="user-login-box">
            <div class="user-login-box-text">
                <div x-data="{ open: false }" class="user-dropdown-wrapper">
                  <a class="user-login-box-text-title"
                    href="#"
                    @click.prevent="open = !open">
                    {{ Auth::user()->name }} 님 <i class="mdi mdi-cog-outline gear-icon"></i>
                  </a>
    
                  <ul class="dropdown-menu user-dropdown-menu"
                      :class="{ 'show': open }">
                    <li><a class="dropdown-item" href="#">내 정보 수정</a></li>
                    <li>
                      <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item" type="submit">로그아웃</button>
                      </form>
                    </li>
                  </ul>
                </div>
            </div>
        </div>
        @endauth
      </div>
      <div class="col-3">
        <div class="offcanvas-close-btn">
          <button type="button" class="mdi mdi-close offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
      </div>
    </div>

  </div>
  <div class="offcanvas-content-wrapper">
    <div class="offcanvas-top">
      @include('components.offcanvas.topBanner')
    </div>

    <div class="offcanvas-bottom">
      <div class="sell-highlight-card">
        <div class="sell-highlight-text">
          <p class="headline">고민은</p>
          <p class="subline">판매만 늦출뿐!</p>
        </div>
        <div class="sell-highlight-image">
          <img src="{{ asset('images/side-nav/side-nav01.png') }}" alt="판매완료 이미지" />
        </div>
      </div>

      <ul class="offcanvas-menu">
        @foreach ($menus as $menu)
          <li>
            <a href="{{ url($prefix . $menu['url']) }}" class="menu-link">
              @if (!empty($menu['icon']))
                <div class="menu-icon">
                  <img src="{{ $menu['icon'] }}" alt="{{ $menu['label'] }} 아이콘" />
                </div>
              @endif
              <div class="menu-text">
                <strong>{{ $menu['label'] }}</strong>
                @if (!empty($menu['desc']))
                  <small>{{ $menu['desc'] }}</small>
                @endif
              </div>
            </a>
          </li>
        @endforeach
      </ul>

      <div class="offcanvas-footer-logo">
        <img src="{{ asset('images/busan_wecar_logo_footer.png') }}" alt="weCar 로고" />
      </div>
    </div>
  </div>
</div>