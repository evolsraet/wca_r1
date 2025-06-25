<div class="category-tab" x-data="{ current: 'all', counts: {
        all: {{ $counts['all'] ?? 0 }},
        sold: {{ $counts['sold'] ?? 0 }}
    } }">
<div class="container">
  <ul class="tab-list">
    <li>
      <button
        class="tab-btn"
        :class="{ active: current === 'all' }"
        @click="current = 'all'"
      >
        전체
        <span class="tab-count" x-text="counts.all ?? 0"></span>
      </button>
    </li>
    <li>
      <button
        class="tab-btn"
        :class="{ active: current === 'sold' }"
        @click="current = 'sold'"
      >
        판매한 매물
        <span class="tab-count" x-text="counts.sold ?? 0"></span>
      </button>
    </li>

    <li x-show="window.userRole === 'user'">
      <a
        href="{{ route('auction.registerform') }}"
        class="tab-btn"
      >
        공매등록
      </a>
    </li>

    <!-- 추가 탭 필요시 여기에 계속 작성 -->
  </ul>
</div>
</div>