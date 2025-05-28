<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('home') }}">WCA</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link {{ request()->is('v2') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('v2/test') ? 'active' : '' }}" href="/v2/test">test</a>
        </li>
      </ul>

      <ul class="navbar-nav">
        @auth
          <li class="nav-item">
            <span class="nav-link">{{ Auth::user()->name }}</span>
          </li>
          <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-outline-danger btn-sm">로그아웃</button>
            </form>
          </li>
        @else
          <li class="nav-item">
            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm me-2">로그인</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('register') }}" class="btn btn-outline-success btn-sm">회원가입</a>
          </li>
        @endauth
      </ul>
    </div>
  </div>
</nav>

