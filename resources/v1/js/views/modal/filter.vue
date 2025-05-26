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
            <div class="mt-4 mb-5">
              <div>
              <h5 class="tc-primary">주행거리</h5>
              <div class="my-2">{{ minDistance.toLocaleString() }} km ~ {{ maxDistance.toLocaleString() }} km</div>
              <div ref="slider" class="custom-slider"></div>
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
      isExpanded: false, // 하단 메뉴 확장 상태
      scrollY: 0, // 스크롤 위치 저장
      scrollTimeout: null, // 스크롤 타임아웃 저장
      selectedYear: new Date().getFullYear(), // 선택된 연도 (현재 연도)
      years: [], // 연도 목록 저장
      minRange: 0, // 최소값 초기화
      maxRange: 200000, // 최대값 초기화
      expandedDropdown: null,
      showModal: true,
      dropdownStates: {
      domestic: true,
      imported: true,
      cargoSpecial:true,
    },
      categories: {
        '경매 이력': [
          { name: '재경매차', selected: false },
          { name: '내 입찰 차', selected: false }
        ],
        '리스 렌트': [
          { name: '2WD', selected: false },
          { name: '4WD', selected: false }
        ],
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
        '판매방식': [
          { name: '일반', selected: false },
          { name: '렌트', selected: false },
          { name: '리스', selected: false },
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
    beforeDestroy() {
      window.removeEventListener('scroll', this.handleScroll);
      clearTimeout(this.scrollTimeout);
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
<script setup>
import { ref, computed, onMounted, reactive, onUnmounted, inject } from 'vue';
import { useStore } from "vuex";
import useAuctions from "@/composables/auctions"; 
import useRoles from '@/composables/roles'; 
import FilterModal from '@/views/modal/filter.vue'; 
import { useRoute, useRouter } from 'vue-router';
import Footer from "@/views/layout/footer.vue";
import useLikes from '@/composables/useLikes';
import usebid from '@/composables/bids.js'; 
import { cmmn } from '@/hooks/cmmn';
import { isError } from 'lodash';
import noUiSlider from "nouislider";
import "nouislider/dist/nouislider.css";
const swal = inject('$swal');
const selectedStartYear = ref(new Date().getFullYear() - 1);
const selectedEndYear = ref(new Date().getFullYear());
const store = useStore();
const user = computed(() => store.getters["auth/user"]); 
const isDealer = computed(() => user.value?.roles?.includes('dealer'));
const slider = ref(null);
const minDistance = ref(20000);
const maxDistance = ref(120000);

onMounted(() => {
  noUiSlider.create(slider.value, {
    start: [minDistance.value, maxDistance.value],
    connect: true,
    range: {
      min: 0,
      max: 200000,
    },
    step: 1000,
    format: {
      to: value => Math.round(value).toLocaleString(),
      from: value => Number(value.replace(/,/g, "")),
    },
  });

  slider.value.noUiSlider.on("update", (values, handle) => {
    if (handle === 0) minDistance.value = Number(values[0].replace(/,/g, ""));
    if (handle === 1) maxDistance.value = Number(values[1].replace(/,/g, ""));
  });
});
</script>
