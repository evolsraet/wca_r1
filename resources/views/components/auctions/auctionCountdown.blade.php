<div 
  x-data="auctionCountdown('2025-06-16 10:00:00')" 
  x-init="start()"
  class="bg-danger text-white rounded-3 px-2 py-1 fs-7 font-weight-normal d-inline-flex align-items-center gap-1"
>
    <i class="mdi mdi-clock-outline"></i>
    <span x-text="timeLeft"></span> 
</div>