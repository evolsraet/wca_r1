<div class="option-icons-box mb-3 mt-3" x-data="auctionOptions()">
    <h5 class="fw-bold mb-4">옵션정보</h5>
  
    <div class="row row-cols-4 row-cols-sm-4 row-cols-md-6 g-3">
      <template x-for="option in options" :key="option.id">
        <div class="col text-center option-item" :class="option.is_ok ? 'active' : 'inactive'">
          <div class="option-icon mb-1" x-html="getRenderedSvg(option)"></div>
          <div class="option-label small text-secondary" x-text="option.name"></div>
        </div>
      </template>
    </div>
  </div>