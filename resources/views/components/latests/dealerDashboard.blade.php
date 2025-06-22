@php
    $user = auth()->user();
    $bidResult = Wca::bidCount($user->id);

    $auctionCount = count($bidResult['auctionList']);
    $bidCount = count($bidResult['bidList']);
    $likeCount = count($bidResult['likeList']);
    $bidDoneCount = count($bidResult['bidDoneList']);
    $bidNotDoneCount = count($bidResult['bidNotDoneList']);
@endphp

<div class="carousel-top-dealer-box">
    <h2 class="dealer-title">
      현재 진행중인 경매가 <span class="count-emphasis">{{ $auctionCount ?? 0 }} 건</span> 있어요.
    </h2>

    <div class="dealer-stats-container">
      <div class="dealer-stat">
        <div class="dealer-count text-danger">{{ $likeCount ?? 0 }}건</div>
        <div class="dealer-icon"><i class="bi bi-heart"></i></div>
        <div class="dealer-label">관심</div>
      </div>
      <div class="dealer-divider"></div>
      <div class="dealer-stat">
        <div class="dealer-count text-danger">{{ $bidCount ?? 0 }}건</div>
        <div class="dealer-icon"><i class="bi bi-tag"></i></div>
        <div class="dealer-label">입찰</div>
      </div>
      <div class="dealer-divider"></div>
      <div class="dealer-stat">
        <div class="dealer-count text-danger">{{ $bidDoneCount ?? 0 }}건</div>
        <div class="dealer-icon"><i class="bi bi-check2-square"></i></div>
        <div class="dealer-label">낙찰</div>
      </div>
      <div class="dealer-divider"></div>
      <div class="dealer-stat">
        <div class="dealer-count text-danger">{{ $bidNotDoneCount ?? 0 }}건</div>
        <div class="dealer-icon"><i class="bi bi-x-circle"></i></div>
        <div class="dealer-label">유찰</div>
      </div>
    </div>
</div>