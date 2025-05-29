@php
  $user = Auth::user();
  $menus = [];

  if (Auth::check()) {
    if ($user->hasRole('user')) {
      $menus = [
        ['key' => 'mycar', 'label' => '내차조회', 'url' => url('/v2/mycar'), 'icon' => asset('images/Icon-awesome-car-side-Black.png'), 'desc' => '내 차량 조회'],
        ['key' => 'listing', 'label' => '내 매물관리', 'url' => url('/v2/listing'), 'icon' => asset('images/Icon-awesome-car-side-Black.png'), 'desc' => ''],
        ['key' => 'reviews', 'label' => '이용후기', 'url' => url('/v2/reviews'), 'icon' => asset('images/rating.png'), 'desc' => '다양한 판매 후기'],
        ['key' => 'docs', 'label' => '명의이전서류', 'url' => url('/v2/docs'), 'icon' => asset('images/Icon-awesome-car-side-Black.png'), 'desc' => ''],
        ['key' => 'notice', 'label' => '공지사항', 'url' => url('/v2/notice'), 'icon' => asset('images/Icon-awesome-car-side-Black.png'), 'desc' => ''],
        ['key' => 'introduce', 'label' => '서비스소개', 'url' => url('/v2/introduce'), 'icon' => asset('images/Icon-md-bulb.png'), 'desc' => '위카란?'],
      ];
    } elseif ($user->hasRole('dealer')) {
      $menus = [
        ['key' => 'bidding', 'label' => '입찰하기', 'url' => url('/v2/bidding'), 'icon' => asset('images/Icon-awesome-car-side-Black.png'), 'desc' => ''],
        ['key' => 'notice', 'label' => '공지사항', 'url' => url('/v2/notice'), 'icon' => asset('images/Icon-awesome-car-side-Black.png'), 'desc' => ''],
        ['key' => 'claim', 'label' => '클레임', 'url' => url('/v2/claim'), 'icon' => asset('images/Icon-awesome-car-side-Black.png'), 'desc' => ''],
        ['key' => 'introduce', 'label' => '서비스소개', 'url' => url('/v2/introduce'), 'icon' => asset('images/Icon-md-bulb.png'), 'desc' => '위카란?'],
      ];
    }
  } else {
    $menus = [
      ['key' => 'mycar', 'label' => '내차조회', 'url' => url('/v2/mycar'), 'icon' => asset('images/Icon-awesome-car-side-Black.png'), 'desc' => '내 차량 조회'],
      ['key' => 'reviews', 'label' => '이용후기', 'url' => url('/v2/reviews'), 'icon' => asset('images/rating.png'), 'desc' => '다양한 판매 후기'],
      ['key' => 'introduce', 'label' => '서비스소개', 'url' => url('/v2/introduce'), 'icon' => asset('images/Icon-md-bulb.png'), 'desc' => '위카란?'],
    ];
  }
@endphp

<nav class="navbar navbar-expand-lg sticky-top {{ $user?->hasRole('dealer') ? 'dealer-header' : 'default-header' }}">
  <div class="container-fluid">
    <a class="navbar-brand logo-text" href="{{ route('home') }}">wecarlogo</a>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        @foreach ($menus as $menu)
          @php
            $isActive = request()->is("v2/{$menu['key']}*");
          @endphp

          <li class="nav-item">
            <a class="nav-link {{ $isActive ? 'active' : '' }}" href="{{ $menu['url'] }}">
              {{ $menu['label'] }}
            </a>
          </li>
        @endforeach
      </ul>

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

    <a class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDark" aria-controls="offcanvasDark">
      <img src="{{ asset('images/toggle-nav-black.png') }}" alt="메뉴" class="toggle-nav-img">
    </a>
  </div>
</nav>

<div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDark" aria-labelledby="offcanvasDarkLabel">
  <div class="offcanvas-header">
    <button type="button" class="btn-close btn-close offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-content-wrapper">
    <div class="offcanvas-top">
      <div class="login-card">
        <div class="login-card-text">
          <p class="login-subtitle">내 차 팔까?</p>
          <a href="{{ route('login') }}" class="login-link">로그인하기</a>
        </div>
        <div class="login-card-image"></div>
      </div>
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
            <a href="{{ $menu['url'] }}" class="menu-link">
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