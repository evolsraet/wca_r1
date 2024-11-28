<template>
  <section class="modal modal-section type-confirm alert-modal-type02" :style="{ display: showModal ? 'block' : 'none' }">
    <div class="modal-dialog">
      <div class="modal-content shadow">
        <div class="modal-body">
          <div class="content mt-0">
            <div class="nav-header">
              <button type="button" class="btn-close" @click="handleCloseClick"></button>
            </div>
            <h5>필터</h5>
            <div v-for="(items, category) in categories" :key="category" class="facturer">
              <h5 class="text-secondary opacity-50">{{ category }}</h5>
              <div class="manufacturer-model my-3">
                <div
                  v-for="(item, index) in items"
                  :key="item.name"
                  class="item"
                  :class="{ 'selected': item.selected }"
                  @click="toggleSelection(category, index)"
                >
                  {{ item.name }}
                </div>
              </div>
            </div>
            <div class="facturer mt-3">
              <h5 class="text-secondary opacity-50">연식</h5>
              <div class="model-year mt-4 pb-4 d-flex align-items-center">
                <select v-model="selectedStartYear" class="form-control custom-select">
                  <option v-for="year in years" :key="'start-' + year" :value="year">{{ year }}년</option>
                </select>
                ~
                <select v-model="selectedEndYear" class="form-control custom-select">
                  <option v-for="year in years" :key="'end-' + year" :value="year">{{ year }}년</option>
                </select>
              </div>
            </div>
            <div class="facturer mt-3">
              <h5 class="text-secondary opacity-50">주행거리</h5>
              <div class="range-slider">
                <!-- 최소값 슬라이더 -->
                <input type="range" min="0" max="200000" v-model="minRange" id="min-range" class="range-min" @input="updateProgressBar">
                <!-- 최대값 슬라이더 -->
                <input type="range" min="0" max="200000" v-model="maxRange" id="max-range" class="range-max" @input="updateProgressBar">
                <div class="slider-value">
                  <span id="range-min-value">{{ formatNumber(minRange) }}km</span> ~ <span id="range-max-value">{{ formatNumber(maxRange) }}km{{ maxRange >= 200000 ? ' 이상' : '' }}</span>
                </div>
              </div>
            </div>
            <div class="btn-group">
              <button class="btn btn-secondary shadow" @click="resetSelection">초기화</button>
              <button class="btn btn-primary w-50 modal_close shadow" @click="handleCloseClick">필터 적용</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  data() {
    return {
      showModal: true,
      categories: {
        '국산차': [
          { name: '현대', selected: false },
          { name: '기아', selected: false },
          { name: '제네시스', selected: false },
          { name: '쉐보레 (GM대우)', selected: false },
          { name: '르노코리아 (삼성)', selected: false },
          { name: 'KG모빌리티(쌍용)', selected: false },
        ],
        '수입차': [
          { name: '벤츠', selected: false },
          { name: 'BMW', selected: false },
          { name: '아우디', selected: false },
          { name: '포르쉐', selected: false },
          { name: '미니', selected: false },
          { name: '랜드로버', selected: false },
          { name: '폭스바겐', selected: false }
        ],
        '화물 특장 기타': [
        { name: '탑차', selected: false },
        { name: '냉동/냉장차', selected: false },
        { name: '윙바디', selected: false },
        { name: '탱크로리', selected: false },
        { name: '카고크레인', selected: false },
        { name: '트레일러', selected: false },
        { name: '평판 트럭', selected: false },
        { name: '컨테이너 트럭', selected: false },
        { name: '축산 차량', selected: false },
        { name: '쓰레기 수거차', selected: false },
        { name: '목재 운반차', selected: false }
        ],
        '지역': [
          { name: '서울', selected: false },
          { name: '경기(북)', selected: false },
          { name: '강원', selected: false },
          { name: '인천', selected: false },
          { name: '경기(남)', selected: false },
          { name: '충남', selected: false },
          { name: '충북', selected: false },
          { name: '세종', selected: false },
          { name: '대전', selected: false },
          { name: '경북', selected: false },
          { name: '전북', selected: false },
          { name: '대구', selected: false },
          { name: '광주', selected: false },
          { name: '경남', selected: false },
          { name: '울산', selected: false },
          { name: '전남', selected: false },
          { name: '제주', selected: false },
          { name: '부산', selected: false }
        ],
        '사고': [
          { name: '무사고', selected: false },
          { name: '유사고', selected: false }
        ],
        '미션': [
          { name: '오토(A/T)', selected: false },
          { name: '수동(M/T)', selected: false }
        ],
        '연료': [
          { name: '가솔린', selected: false },
          { name: '디젤', selected: false },
          { name: 'LPG(일반인 구입)', selected: false },
          { name: '가솔린+전기', selected: false },
          { name: '가솔린+LPG', selected: false },
          { name: '전기', selected: false },
          { name: '기타', selected: false }
        ],
        '경매 이력': [
          { name: '재경매차', selected: false },
          { name: '내 입찰 차', selected: false }
        ],
        '판매방식': [
          { name: '일반', selected: false },
          { name: '렌트', selected: false },
          { name: '리스', selected: false },
        ],
        '리스 렌트': [
          { name: '2WD', selected: false },
          { name: '4WD', selected: false }
        ],
      },
      selectedStartYear: null,
      selectedEndYear: null,
      years: Array.from({ length: 22 }, (_, i) => 2023 - i), // 최근 22년 연도 목록
      minRange: 0, // 최소값 초기화
      maxRange: 200000 // 최대값 초기화
    };
  },
  mounted() {
    this.updateProgressBar(); // 초기화 시 프로그레스 바 업데이트
  },
  methods: {
    handleCloseClick() {
      this.showModal = false;
      this.$emit('close');
    },
    toggleSelection(category, index) {
      this.categories[category][index].selected = !this.categories[category][index].selected;
    },
    resetSelection() {
      Object.keys(this.categories).forEach((category) => {
        this.categories[category].forEach((item) => (item.selected = false));
      });
      this.minRange = 0;
      this.maxRange = 200000;
      this.updateProgressBar();
    },
    applyFilters() {
      let selectedFilters = {};
      Object.keys(this.categories).forEach((category) => {
        selectedFilters[category] = this.categories[category]
          .filter((item) => item.selected)
          .map((item) => item.name);
      });
      this.showModal = false;
      this.$emit('update-filters', selectedFilters);
    },
    formatNumber(value) {
      return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    },
    updateProgressBar() {
      const rangeSlider = document.querySelector('.range-slider');
      const minPercent = (this.minRange / 200000) * 100;
      const maxPercent = (this.maxRange / 200000) * 100;

      // 슬라이더 배경 업데이트
      rangeSlider.style.background = `linear-gradient(to right, #d3d3d3 0%, #d3d3d3 ${minPercent}%, #f24200 ${minPercent}%, #f24200 ${maxPercent}%, #d3d3d3 ${maxPercent}%, #d3d3d3 100%)`;
    }
  }
};
</script>
