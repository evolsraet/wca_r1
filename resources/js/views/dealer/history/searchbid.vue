<!--
TODO:
- 관심 차량 숫자 표기
- [사용자] - 딜러 선택이 가능해요, 후기남기기 (라우터 어디로..?)
- 옵션:최신등록순, 가격낮은순 처리
-->

<template>
  <!-- 서브 네비게이션 바 -->
  <!-- 슬라이드업 메뉴 트리거 버튼 및 컨테이너 -->
  <!-- <div v-if="isUser"  class="review-button-container" :style="{ height: menuHeight }" @click="toggleMenuHeight">
      <div class="icon-container" v-show="isExpanded" ></div>
      <router-link :to="{ name: 'home' }" tag="button" class="black-btn tc-wh" v-show="!isExpanded" :disabled="isExpanded">딜러 선택이 가능해요!<span class="btn-apply-ty02">바로가기</span></router-link>
     
     <router-link :to="{ name: 'index.allreview' }" tag="button" class="review-btn tc-red" v-show="!isExpanded && hasCompletedAuctions" :disabled="isExpanded">후기 남기기</router-link>      
   
     <div class="review-none" v-show="!isExpanded && !hasCompletedAuctions" :disabled="isExpanded" @click.stop="">후기 남기기</div>
  </div>-->
  <div class="container my-5 auction-content">
    <div class="px-2 d-flex justify-content-between">
      <div>      
        <h5>소·도매 시세를 확인해보세요</h5>
        <h3 class="bolder">내 차 판매 전, 시세조회</h3>
      </div>
      <div class="d-flex gap-2">      
        <div class="text-state">
        <div>도매시세</div>
        <div class="image-state">
            <img src="../../../../img/Auto_logo.png" alt="Autohub Auction Logo" width="90">
        </div>
      </div>
      <div class="text-state">
        <div>소매시세</div>
        <div class="image-state">
            <img src="../../../../img/nice_logo.png" alt="Autohub Auction Logo" width="100">
        </div>
      </div>
    </div>
    </div>
  <div class="content-main mt-3" :class="{ row: isDealer }">
     <Filter/>
          <!-- 메인 컨텐츠 -->
          <div class="col-md-9 auction-main">
             <div class="text-state p-4 align-items-end">
                <div>
                  <p class="bold">현대 그랜저 HG 2013년식</p>
                  <p class="bolder tc-primary size_26">580<span class="size_14 ms-1">만원</span> ~ 900<span class="size_14 ms-1">만원</span></p>
                </div>
                <p class="size_14 tc-primary bc-primary-sb02 py-1 px-2 border-6 mb-2">무사고 기준</p>
             </div> 
             <div class="mt-4 d-flex justify-content-between align-items-end">                    
              <div class="custom-select-box">
                <select>
                  <option>최근 견적 순</option>
                  <option>낮은 가격 순</option>
                  <option>높은 가격 순</option>
                </select>
              </div>
              <p>검색결과 552대</p>
          </div>
          
          <div class="mt-1">
   
            <div class="text-state p-4 mb-3 align-items-end">
              <div class="image-container d-flex">
                <div class="card my-auction">
                  <img src="../../../../img/car_example.png" class="card-img-top-placeholder" style="height: 180px !important;"/>
                  <span class="mx-2 timer">진단완료</span>
                </div>
                <div class="ms-3 d-flex flex-column justify-content-center justify-content-between">
                  <div>
                    <h4 style="margin: 0;">현대 그랜저 HG 2013년식</h4>
                    <p class="tc-gray mt-2">대전<span class="mx-2">|</span>140,000km<span class="mx-2">|</span>휘발유</p>
                  </div>
                <div class="d-flex">
                <span class="blue-box mb-0">무사고</span>
                <span class="gray-box border-6">1인소유</span>
                </div>
              </div>
              </div>
              <div class="details-container">
                <div style="display: flex; gap: 8px; margin-top: 8px;">
                </div>
              </div>
              <div class="d-flex">
                <p class="tc-gray bold">도매가 <span class="tc-primary size_26 mx-2">820</span><span class="tc-primary">만원 </span><span class="mx-2">|</span> 소매가<span class="tc-primary size_26 mx-2">820</span><span class="tc-primary">만원 </span></p>
              </div>
            </div>
            
            <div class="text-state p-4 mb-3 align-items-end">
              <div class="image-container d-flex">
                <div class="card my-auction">
                  <img src="../../../../img/car_example.png" class="card-img-top-placeholder"  style="height: 180px !important;"/>
                  <span class="mx-2 timer">진단완료</span>
                </div>
                <div class="ms-3 d-flex flex-column justify-content-center justify-content-between">
                  <div>
                    <h4 style="margin: 0;">현대 그랜저 HG 2013년식</h4>
                    <p class="tc-gray mt-2">대전<span class="mx-2">|</span>140,000km<span class="mx-2">|</span>휘발유</p>
                  </div>
                <div class="d-flex">
                <span class="blue-box mb-0">무사고</span>
                <span class="gray-box border-6">1인소유</span>
                </div>
              </div>
              </div>
              <div class="details-container">
                <div style="display: flex; gap: 8px; margin-top: 8px;">
                </div>
              </div>
              <div class="d-flex">
                <p class="tc-gray bold">도매가 <span class="tc-primary size_26 mx-2">820</span><span class="tc-primary">만원 </span><span class="mx-2">|</span> 소매가<span class="tc-primary size_26 mx-2">820</span><span class="tc-primary">만원 </span></p>
              </div>
            </div>

          </div>
        </div>
      </div>
  </div>
  <Footer />
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
    dropdownStates: {
      domestic: true,
      imported: true,
      cargoSpecial:true,
    },
      domesticCars: [
      { id: 'hyundai', label: '현대', value: 'hyundai', count: '1,356' },
      { id: 'kia', label: '기아', value: 'kia', count: '1,356' },
      { id: 'genesis', label: '제네시스', value: 'genesis', count: '1,356' },
      { id: 'chevrolet', label: '쉐보레(GM대우)', value: 'chevrolet', count: '1,356' },
      { id: 'renault', label: '르노코리아(삼성)', value: 'renault', count: '1,356' },
      { id: 'kg_mobility', label: 'KG모빌리티(쌍용)', value: 'kg_mobility', count: '1,356' },
    ],
    importedCars: [
      { id: 'mercedes', label: '벤츠', value: 'mercedes', count: '1,356' },
      { id: 'bmw', label: 'BMW', value: 'bmw', count: '1,356' },
      { id: 'audi', label: '아우디', value: 'audi', count: '1,356' },
      { id: 'porsche', label: '포르쉐', value: 'porsche', count: '1,356' },
      { id: 'mini', label: '미니', value: 'mini', count: '1,356' },
      { id: 'landrover', label: '랜드로버', value: 'landrover', count: '1,356' },
      { id: 'volkswagen', label: '폭스바겐', value: 'volkswagen', count: '1,356' },
    ],
    cargoSpecialCars: [
      { id: 'box_truck', label: '탑차', value: 'box_truck', count: '1,356' },
      { id: 'refrigerated_truck', label: '냉동/냉장차', value: 'refrigerated_truck', count: '1,356' },
      { id: 'wing_body', label: '윙바디', value: 'wing_body', count: '1,356' },
      { id: 'tank_lorry', label: '탱크로리', value: 'tank_lorry', count: '1,356' },
      { id: 'cargo_crane', label: '카고크레인', value: 'cargo_crane', count: '1,356' },
      { id: 'trailer', label: '트레일러', value: 'trailer', count: '1,356' },
      { id: 'flatbed_truck', label: '평판 트럭', value: 'flatbed_truck', count: '1,356' },
      { id: 'container_truck', label: '컨테이너 트럭', value: 'container_truck', count: '1,356' },
      { id: 'livestock_truck', label: '축산 차량', value: 'livestock_truck', count: '1,356' },
      { id: 'garbage_truck', label: '쓰레기 수거차', value: 'garbage_truck', count: '1,356' },
      { id: 'logging_truck', label: '목재 운반차', value: 'logging_truck', count: '1,356' }
      ],
  };
},

computed: {
  menuHeight() { // 하단 메뉴 높이 계산
    return this.isExpanded ? '0px' : '115px';
  }
},

created() {
  window.addEventListener('scroll', this.handleScroll); // 스크롤 이벤트 리스너 추가
  this.years = this.generateYearRange(1990, new Date().getFullYear()); // 연도 목록 생성
  this.fetchCarData();
},

methods: {
  toggleDropdown(type) {
    // 상태 토글
    this.dropdownStates[type] = !this.dropdownStates[type];
  },

  async fetchCarData() {
    try {
      // API 호출로 데이터를 가져옴 (예: axios 사용)
      const domesticResponse = await fetch('/api/cars/domestic'); // 국산차 데이터 요청
      const importedResponse = await fetch('/api/cars/imported'); // 수입차 데이터 요청

      // 응답 데이터를 JSON으로 변환
      const domesticData = await domesticResponse.json();
      const importedData = await importedResponse.json();

      // Vue 상태에 데이터 반영
      this.domesticCars = domesticData.map(car => ({
        id: car.id,
        label: car.name,
        value: car.value,
        count: car.count.toLocaleString(), // 숫자 포맷
      }));

      this.importedCars = importedData.map(car => ({
        id: car.id,
        label: car.name,
        value: car.value,
        count: car.count.toLocaleString(),
      }));
    } catch (error) {
      console.error('데이터를 가져오는 중 오류 발생:', error);
    }
  },

  toggleMenuHeight() { // 하단 메뉴 높이 토글
    this.isExpanded = !this.isExpanded;
  },
  formatNumber(value) {
    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  },
  submitReview() { // 리뷰 제출 시 메뉴 높이 토글
    this.toggleMenuHeight();
  },

  generateYearRange(start, end) {
    const years = [];
    for (let year = start; year <= end; year++) {
      years.push(year);
    }
    return years;
  },

  handleScroll() {
    clearTimeout(this.scrollTimeout);
    this.scrollTimeout = setTimeout(() => {
      this.scrollY = window.scrollY;
    }, 200);
  },
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
};
</script>

<script setup>
import { ref, computed, onMounted, reactive, onUnmounted , inject } from 'vue';
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
import Filter from "@/views/import/filter.vue";
const swal = inject('$swal');
const selectedStartYear = ref(new Date().getFullYear() - 1);
const selectedEndYear = ref(new Date().getFullYear());
const store = useStore();
const user = computed(() => store.getters["auth/user"]); 
const isDealer = computed(() => user.value?.roles?.includes('dealer')); 

</script>

<style scoped>
.text-state{
  display: flex;
  align-items: center;
  justify-content: space-between;
  border: 1px solid #E5E7EB;
  border-radius: 8px;
  padding: 16px;
  width: auto;
  gap: 50px;
}
</style>
