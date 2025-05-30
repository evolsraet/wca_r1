<div class="search-bar" x-data="{ keyword: '' }">
  <i class="bi bi-search search-icon"></i>
  <input
    type="text"
    class="search-input"
    placeholder="모델명, 차량번호, 지역"
    x-model="keyword"
    @input="$dispatch('search', keyword)"
  >
</div>