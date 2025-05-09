<template>
 <!-- 사이드바 -->
 <div v-if="isDealer" class="sider-content col-md-3 mov-info02">

    <div class="proceeding"></div>
    
          <div class="sub-side">


            <!-- <div class=" mt-4">
                <h5 class="tc-primary">경매 이력</h5>
                <div class="mt-4 pb-3 bd-gray">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="manufacturer" id="hyundai" value="hyundai" checked>
                        <label class="form-check-label" for="hyundai">재경매차</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                        <label class="form-check-label" for="kia">내 입찰 차</label>
                    </div>
                </div>
            </div>

            <div class=" mt-4">
                <h5 class="tc-primary">구동방식</h5>
                <div class="row mt-4 pb-3">
                    <div class="col-5">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="manufacturer" id="hyundai" value="hyundai" checked>
                            <label class="form-check-label" for="hyundai">2WD</label>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                            <label class="form-check-label" for="kia">4WD</label>
                        </div>
                    </div>
                </div>
            </div> -->
          <!-- 국산차 드롭다운 -->
          <div class="mt-4">
              <div class="dropdown-section slide-gray my-1" @click="toggleDropdown('domestic')">
                  <h5 class="tc-primary line-heigh-0">국산차</h5>
                  <span class="dropdown-icon">
                  <img v-if="dropdownStates.domestic" src="../../../img/Icon-black-up.png" alt="Up Icon" width="13"/>
                  <img v-else src="../../../img/Icon-black-down.png" alt="Down Icon" width="13"/></span>
              </div>
              <div
                  class="dropdown-content bd-gray"
                  :class="{ show: dropdownStates.domestic }"
              >
                  <div class="form-check" v-for="item in domesticCars" :key="item.id">
                  <input
                      class="form-check-input"
                      type="radio"
                      name="manufacturer-domestic"
                      :id="item.id"
                      :value="item.value"
                  />
                  <label class="form-check-label d-flex justify-content-between" :for="item.id">
                      {{ item.label }}
                      <span class="text-secondary mx-2">{{ item.count }}</span>
                  </label>
                  </div>
              </div>
          </div>
          <!-- 수입차 드롭다운 -->
          <div>
              <div class="dropdown-section slide-gray mt-3" @click="toggleDropdown('imported')">
                  <h5 class="tc-primary line-heigh-0">수입차</h5>
                  <span class="dropdown-icon">
                    <img v-if="dropdownStates.imported" src="../../../img/Icon-black-up.png" alt="Up Icon" width="13"/>
                    <img v-else src="../../../img/Icon-black-down.png" alt="Down Icon" width="13"/>
                </span>
              </div>
              <div
                  class="dropdown-content bd-gray"
                  :class="{ show: dropdownStates.imported }"
              >
                  <div class="form-check" v-for="item in importedCars" :key="item.id">
                  <input
                      class="form-check-input"
                      type="radio"
                      name="manufacturer-imported"
                      :id="item.id"
                      :value="item.value"
                  />
                  <label class="form-check-label d-flex justify-content-between" :for="item.id">
                      {{ item.label }}
                      <span class="text-secondary mx-2">{{ item.count }}</span>
                  </label>
                  </div>
              </div>
          </div>
          <div>
          <div class="dropdown-section slide-gray mt-3" @click="toggleDropdown('cargoSpecial')">
              <h5 class="tc-primary line-heigh-0">화물 특장 기타</h5>
              <span class="dropdown-icon">
                    <img v-if="dropdownStates.cargoSpecial" src="../../../img/Icon-black-up.png" alt="Up Icon" width="13"/>
                    <img v-else src="../../../img/Icon-black-down.png" alt="Down Icon" width="13"/>
                </span>
          </div>
              <div
                  class="dropdown-content bd-gray"
                  :class="{ show: dropdownStates.cargoSpecial }"
              >
                  <div class="form-check" v-for="item in cargoSpecialCars" :key="item.id">
                      <input
                          class="form-check-input"
                          type="radio"
                          name="manufacturer-cargoSpecial"
                          :id="item.id"
                          :value="item.value"
                      />
                      <label class="form-check-label d-flex justify-content-between" :for="item.id">
                          {{ item.label }}
                          <span class="text-secondary mx-2">{{ item.count }}</span>
                      </label>
                  </div>
              </div>
          </div>

                  <h5 class="tc-primary mt-4">분류</h5>
                  <div class="mt-4 pb-3 bd-gray">
                      <div class="form-check">
                          <input class="form-check-input" type="radio" name="manufacturer" id="domestic" value="domestic" checked>
                          <label class="form-check-label d-flex justify-content-between" for="domestic">국산차 <span class="text-secondary mx-2">1,356</span></label>
                      </div>
                      <div class="form-check">
                          <input class="form-check-input" type="radio" name="manufacturer" id="imported" value="imported">
                          <label class="form-check-label d-flex justify-content-between" for="imported">수입차 <span class="text-secondary mx-2">1,356</span></label>
                      </div>
                      <div class="form-check">
                          <input class="form-check-input" type="radio" name="manufacturer" id="special_trucks" value="special_trucks">
                          <label class="form-check-label d-flex justify-content-between" for="special_trucks">화물 특장 기타 <span class="text-secondary mx-2">1,356</span></label>
                      </div>
                  </div>


                  <!-- 지역 섹션 -->
                  <div class="region mt-5">
                      <h5 class="tc-primary">지역</h5>
                      <div class="row mt-4 pb-3 bd-gray">
                          <!-- 서울 라디오 버튼 -->
                          <div class="col">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="region" id="seoul" value="seoul" checked>
                                  <label class="form-check-label" for="seoul">서울</label>
                              </div>
                          </div>
                          <!-- 경기(북) 라디오 버튼 -->
                          <div class="col">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="region" id="gyeonggi-north" value="gyeonggi-north">
                                  <label class="form-check-label" for="gyeonggi-north">경기(북)</label>
                              </div>
                          </div>
                          <!-- 강원 라디오 버튼 -->
                          <div class="col">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="region" id="gangwon" value="gangwon">
                                  <label class="form-check-label" for="gangwon">강원</label>
                              </div>
                          </div>
                          <!-- 인천 라디오 버튼 -->
                          <div class="col">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="region" id="incheon" value="incheon">
                                  <label class="form-check-label" for="incheon">인천</label>
                              </div>
                          </div>
                          <!-- 경기(남) 라디오 버튼 -->
                          <div class="col">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="region" id="gyeonggi-south" value="gyeonggi-south">
                                  <label class="form-check-label" for="gyeonggi-south">경기(남)</label>
                              </div>
                          </div>
                          <!-- 충남 라디오 버튼 -->
                          <div class="col">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="region" id="chungnam" value="chungnam">
                                  <label class="form-check-label" for="chungnam">충남</label>
                              </div>
                          </div>
                          <!-- 충북 라디오 버튼 -->
                          <div class="col">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="region" id="chungbuk" value="chungbuk">
                                  <label class="form-check-label" for="chungbuk">충북</label>
                              </div>
                          </div>
                          <!-- 세종 라디오 버튼 -->
                          <div class="col">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="region" id="sejong" value="sejong">
                                  <label class="form-check-label" for="sejong">세종</label>
                              </div>
                          </div>
                          <!-- 대전 라디오 버튼 -->
                          <div class="col">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="region" id="daejeon" value="daejeon">
                                  <label class="form-check-label" for="daejeon">대전</label>
                              </div>
                          </div>
                          <!-- 경북 라디오 버튼 -->
                          <div class="col">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="region" id="gyeongbuk" value="gyeongbuk">
                                  <label class="form-check-label" for="gyeongbuk">경북</label>
                              </div>
                          </div>
                          <!-- 전북 라디오 버튼 -->
                          <div class="col">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="region" id="jeonbuk" value="jeonbuk">
                                  <label class="form-check-label" for="jeonbuk">전북</label>
                              </div>
                          </div>
                          <!-- 대구 라디오 버튼 -->
                          <div class="col">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="region" id="daegu" value="daegu">
                                  <label class="form-check-label" for="daegu">대구</label>
                              </div>
                          </div>
                          <!-- 광주 라디오 버튼 -->
                          <div class="col">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="region" id="gwangju" value="gwangju">
                                  <label class="form-check-label" for="gwangju">광주</label>
                              </div>
                          </div>
                          <!-- 경남 라디오 버튼 -->
                          <div class="col">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="region" id="gyeongnam" value="gyeongnam">
                                  <label class="form-check-label" for="gyeongnam">경남</label>
                              </div>
                          </div>
                          <!-- 울산 라디오 버튼 -->
                          <div class="col">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="region" id="ulsan" value="ulsan">
                                  <label class="form-check-label" for="ulsan">울산</label>
                              </div>
                          </div>
                          <!-- 전남 라디오 버튼 -->
                          <div class="col">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="region" id="jeonnam" value="jeonnam">
                                  <label class="form-check-label" for="jeonnam">전남</label>
                              </div>
                          </div>
                          <!-- 제주 라디오 버튼 -->
                          <div class="col">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="region" id="jeju" value="jeju">
                                  <label class="form-check-label" for="jeju">제주</label>
                              </div>
                          </div>
                          <!-- 부산 라디오 버튼 -->
                          <div class="col">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="region" id="busan" value="busan">
                                  <label class="form-check-label" for="busan">부산</label>
                              </div>
                          </div>
                      </div>
                      <div class="year-select mt-5 bd-gray">
                          <h5 class="tc-primary">연식</h5>
                          <div class="model-year mt-4 pb-4 d-flex align-items-center">
                              <select v-model="selectedStartYear" class="form-control custom-select">
                                  <option v-for="year in years" :key="'start-'+year" :value="year">
                                      {{ year }}년
                                  </option>
                              </select>
                              ~
                              <select v-model="selectedEndYear" class="form-control custom-select">
                                  <option v-for="year in years" :key="'end-'+year" :value="year">
                                      {{ year }}년
                                  </option>
                              </select>
                          </div>
                      </div>
                      <div class=" mt-4">
                          <h5 class="tc-primary">사고</h5>
                          <div class="mt-4 pb-3 bd-gray">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="manufacturer" id="no-accident" value="no-accident" checked>
                                  <label class="form-check-label" for="no-accident">무사고</label>
                              </div>
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="manufacturer" id="with-accident" value="with-accident">
                                  <label class="form-check-label" for="with-accident">유사고</label>
                              </div>
                          </div>
                      </div>
                      <div class=" mt-4">
                          <h5 class="tc-primary">미션</h5>
                          <div class="mt-4 pb-3 bd-gray">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="manufacturer" id="hyundai" value="hyundai" checked>
                                  <label class="form-check-label" for="hyundai">오토 (A/T)</label>
                              </div>
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                                  <label class="form-check-label" for="kia">수동 (M/T)</label>
                              </div>
                          </div>
                      </div>
                      <div class=" mt-4">
                        <div>
                        <h5 class="tc-primary">주행거리</h5>
                        <div class="my-2">{{ minDistance.toLocaleString() }} km ~ {{ maxDistance.toLocaleString() }} km</div>
                        <div ref="slider" class="custom-slider"></div>
                    </div>

                      </div>
                      <div class=" mt-4">
                          <h5 class="tc-primary">연료</h5>
                          <div class="mt-4">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="manufacturer" id="gasoline" value="gasoline" checked>
                                  <label class="form-check-label d-flex justify-content-between" for="gasoline">가솔린 <span class="text-secondary mx-2">356</span></label>
                              </div>
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="manufacturer" id="diesel" value="diesel">
                                  <label class="form-check-label d-flex justify-content-between" for="diesel">디젤<span class="text-secondary mx-2">356</span></label>
                              </div>
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="manufacturer" id="lpg" value="lpg">
                                  <label class="form-check-label d-flex justify-content-between" for="lpg">LPG(일반인 구입)<span class="text-secondary mx-2">356</span></label>
                              </div>
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="manufacturer" id="gasoline-electric" value="gasoline-electric">
                                  <label class="form-check-label d-flex justify-content-between" for="gasoline-electric">가솔린+전기<span class="text-secondary mx-2">356</span></label>
                              </div>
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="manufacturer" id="gasoline-lpg" value="gasoline-lpg">
                                  <label class="form-check-label d-flex justify-content-between" for="gasoline-lpg">가솔린+LPG<span class="text-secondary mx-2">356</span></label>
                              </div>
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="manufacturer" id="electric" value="electric">
                                  <label class="form-check-label d-flex justify-content-between" for="electric">전기<span class="text-secondary mx-2">356</span></label>
                              </div>
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="manufacturer" id="other" value="other">
                                  <label class="form-check-label d-flex justify-content-between" for="other">기타<span class="text-secondary mx-2">356</span></label>
                              </div>
                          </div>
                      </div>
                      
                      <div class="mt-4">
                          <h5 class="tc-primary">리스렌트</h5>
                          <div class="mt-4 pb-3 bd-gray">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="manufacturer" id="general" value="general" checked>
                                  <label class="form-check-label d-flex justify-content-between" for="general">일반 <span class="text-secondary mx-2">356</span></label>
                              </div>
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="manufacturer" id="rental" value="rental">
                                  <label class="form-check-label d-flex justify-content-between" for="rental">렌트<span class="text-secondary mx-2">356</span></label>
                              </div>
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="manufacturer" id="lease" value="lease">
                                  <label class="form-check-label d-flex justify-content-between" for="lease">리스<span class="text-secondary mx-2">356</span></label>
                              </div>
                          </div>
                      </div>
                      
                  </div>
              </div>
          </div>

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
},

methods: {
  toggleDropdown(type) {
    // 상태 토글
    this.dropdownStates[type] = !this.dropdownStates[type];
  },

  async fetchCarData() {

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
  if (slider.value) {
    // noUiSlider 초기화
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

    // 슬라이더 업데이트 이벤트
    slider.value.noUiSlider.on("update", (values, handle) => {
      if (handle === 0) minDistance.value = Number(values[0].replace(/,/g, ""));
      if (handle === 1) maxDistance.value = Number(values[1].replace(/,/g, ""));
    });
  } else {
    console.error("Slider element is not found.");
  }
});

</script>
