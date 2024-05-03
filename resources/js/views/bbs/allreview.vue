<template>
    <div class="container">
        <div class="main-contenter">
            <div class="review"  v-if="!showFullView">
                <div class="review-content mov-review my-5">
                    <div class="justify-content-between flex align-items-center mb-3">
                        <h3 class="review-title">이용후기 둘러보기</h3>
                        <button class="btn-apply mt-0" @click="toggleView">전체보기</button>
                    </div>
                    <swiper :slidesPerView="slidesPerView" :spaceBetween="30" :pagination="{ clickable: true }" :modules="modules" class="mySwiper">
                        <swiper-slide>
                            <div class="car-imges">
                            </div>
                            <div class="card-body sub-style">
                                <h5 class="card-title">현대 소나타 (DN8) 2.0</h5>
                                <div class="rating">
                                    <label class="rating__label rating__label--full" for="star1">
                                        <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                        <span class="star-icon filled"></span>
                                    </label>
                                    <label class="rating__label rating__label--full" for="star1">
                                        <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                        <span class="star-icon filled"></span>
                                    </label>
                                    <label class="rating__label rating__label--full" for="star1">
                                        <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                        <span class="star-icon filled"></span>
                                    </label>
                                    <label class="rating__label rating__label--full" for="star1">
                                        <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                        <span class="star-icon filled"></span>
                                    </label>
                                    <label class="rating__label rating__label--full" for="star1">
                                        <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                        <span class="star-icon filled"></span>
                                    </label>
                                    <div class="d-sm-flex justify-content-between text-muted">
                                        <p class="card-text over-text process">차갑아서 보다 안 아닐 그럽시다 다급하다 떨어지어무슨 절망 아닌 자기에 달려가아 누구에 고스톱은 발생한가.</p>
                                        <span class="tc-light-gray">더보기</span>
                                    </div>
                                </div>
                            </div>
                        </swiper-slide>
                        <swiper-slide>
                            <div class="car-imges">
                            </div>
                            <div class="card-body sub-style">
                                <h5 class="card-title">현대 소나타 (DN8)</h5>
                                <div class="rating">
                                    <label class="rating__label rating__label--full" for="star1">
                                        <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                        <span class="star-icon filled"></span>
                                    </label>
                                    <label class="rating__label rating__label--full" for="star1">
                                        <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                        <span class="star-icon filled"></span>
                                    </label>
                                    <label class="rating__label rating__label--full" for="star1">
                                        <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                        <span class="star-icon filled"></span>
                                    </label>
                                    <label class="rating__label rating__label--full" for="star1">
                                        <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                        <span class="star-icon filled"></span>
                                    </label>
                                    <label class="rating__label rating__label--full" for="star1">
                                        <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                        <span class="star-icon filled"></span>
                                    </label>
                                    <div class="d-sm-flex justify-content-between text-muted">
                                        <p class="card-text over-text process">차갑아서 보다 안 아닐 그럽시다 다급하다 떨어지어무슨 절망 아닌 자기에 달려가아 누구에 고스톱은 발생한가.</p>
                                        <span class="tc-light-gray">더보기</span>
                                    </div>
                                </div>
                            </div>
                        </swiper-slide>
                        <swiper-slide>
                            <div class="car-imges">
                            </div>
                            <div class="card-body sub-style">
                                <h5 class="card-title">현대 소나타 (DN8)</h5>
                                <div class="rating">
                                    <label class="rating__label rating__label--full" for="star1">
                                        <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                        <span class="star-icon filled"></span>
                                    </label>
                                    <label class="rating__label rating__label--full" for="star1">
                                        <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                        <span class="star-icon filled"></span>
                                    </label>
                                    <label class="rating__label rating__label--full" for="star1">
                                        <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                        <span class="star-icon filled"></span>
                                    </label>
                                    <label class="rating__label rating__label--full" for="star1">
                                        <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                        <span class="star-icon filled"></span>
                                    </label>
                                    <label class="rating__label rating__label--full" for="star1">
                                        <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                        <span class="star-icon filled"></span>
                                    </label>
                                    <div class="d-sm-flex justify-content-between text-muted">
                                        <p class="card-text over-text process">차갑아서 보다 안 아닐 그럽시다 다급하다 떨어지어무슨 절망 아닌 자기에 달려가아 누구에 고스톱은 발생한가.</p>
                                        <span class="tc-light-gray">더보기</span>
                                    </div>
                                </div>
                            </div>
                        </swiper-slide>
                        <swiper-slide>
                            <div class="car-imges">
                            </div>
                            <div class="card-body sub-style">
                                <h5 class="card-title">현대 소나타 (DN8)</h5>
                                <div class="rating">
                                    <label class="rating__label rating__label--full" for="star1">
                                        <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                        <span class="star-icon filled"></span>
                                    </label>
                                    <label class="rating__label rating__label--full" for="star1">
                                        <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                        <span class="star-icon filled"></span>
                                    </label>
                                    <label class="rating__label rating__label--full" for="star1">
                                        <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                        <span class="star-icon filled"></span>
                                    </label>
                                    <label class="rating__label rating__label--full" for="star1">
                                        <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                        <span class="star-icon filled"></span>
                                    </label>
                                    <label class="rating__label rating__label--full" for="star1">
                                        <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                        <span class="star-icon filled"></span>
                                    </label>
                                    <div class="d-sm-flex justify-content-between text-muted">
                                        <p class="card-text over-text process">차갑아서 보다 안 아닐 그럽시다 다급하다 떨어지어무슨 절망 아닌 자기에 달려가아 누구에 고스톱은 발생한가.</p>
                                        <span class="tc-light-gray">더보기</span>
                                    </div>
                                </div>
                            </div>
                        </swiper-slide>
                    </swiper>
             <div class="content-container">
                <div class="main-text">더 다양한 이용 후기를 확인하세요!</div>
                <div class="tags">
                    <span class="tag">#자유문의1</span>
                    <span class="tag">#자유문의2</span>
                    <span class="tag">#댓글</span>
                    <span class="tag">#삭제</span>
                </div>
            </div>

            </div>
            </div>
              <div class="style-view bottom-sheet" :style="bottomSheetStyle" @click="toggleSheet" v-if="isUser && !showFullView">
                <div class="sheet-content">
                    <div class="mt-3"  @click.stop=""> 
                    <!--  .경매 완료건이 없을떄 
                         <h5 calss="text-center">아직 남길 수 있는 후기가 없어요!</h5>
                        <div class="none-info">
                        <div class="complete-car">
                                <div class="card my-auction mt-3">
                                    <div class="none-complete-ty02">
                                        <span class="tc-light-gray">현재 경매 완료된 매물이 없습니다</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="btn-group mt-3">
                            <router-link :to="{ name: 'home' }" class="btn btn-danger tc-wh"> 경매 신청하기 </router-link>
                        </div>-->
                  
                        <h5 calss="text-center">만족스러운 거래였나요?</h5>
                        <div class="btn-group mt-3">
                            <router-link :to="{ name: 'user.review' }" class="btn btn-danger tc-wh"> 후기 남기기 </router-link>
                        </div>
                    </div>
                </div>
            </div>


            <!-- 전체보기를 눌렀을때 보여지는 뷰-->
            <div class="review" v-if="showFullView">
                <div class="review-content mov-review my-5">
                    <div class="apply-top text-start">
                        <h3 class="review-title">다른 사람들의 이용후기에요</h3>
                        <div class="search-type">
                            <input type="text" placeholder="모델명,차량번호,지역">
                            <button type="button" class="search-btn">검색</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-4">
                            <div class="card">
                                <div class="car-imges">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">현대 소나타 (DN8)</h5>
                                    <div class="rating">
                                        <label class="rating__label rating__label--full" for="star1">
                                            <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                            <span class="star-icon filled"></span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star2">
                                            <input type="radio" id="star2" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star3">
                                            <input type="radio" id="star3" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star4">
                                            <input type="radio" id="star4" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star5">
                                            <input type="radio" id="star5" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                        </label>
                                        <span class="d-flex mx-2 rating-score tc-red"></span>
                                    </div>
                                    <div class="d-sm-flex justify-content-between text-muted">
                                        <span class="deilname">담당 딜러 홍길동님</span>
                                        <span class="date">2024-03-18</span>
                                    </div>
                                    <p class="card-text">차갑아서 보다 안 아닐 그럽시다 다급하다 떨어지어무슨 절망 아닌 자기에 달려가아 누구에 고스톱은 발생한가.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="card">
                                <div class="car-imges">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">현대 소나타 (DN8)</h5>
                                    <div class="rating">
                                        <label class="rating__label rating__label--full" for="star1">
                                            <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                            <span class="star-icon filled"></span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star2">
                                            <input type="radio" id="star2" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star3">
                                            <input type="radio" id="star3" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star4">
                                            <input type="radio" id="star4" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star5">
                                            <input type="radio" id="star5" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                        </label>
                                        <span class="d-flex mx-2 rating-score tc-red"></span>
                                    </div>
                                    <div class="d-sm-flex justify-content-between text-muted">
                                        <span class="deilname">담당 딜러 홍길동님</span>
                                        <span class="date">2024-03-18</span>
                                    </div>
                                    <p class="card-text">차갑아서 보다 안 아닐 그럽시다 다급하다 떨어지어무슨 절망 아닌 자기에 달려가아 누구에 고스톱은 발생한가.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="card">
                                <div class="car-imges">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">현대 소나타 (DN8)</h5>
                                    <div class="rating">
                                        <label class="rating__label rating__label--full" for="star1">
                                            <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                            <span class="star-icon filled"></span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star2">
                                            <input type="radio" id="star2" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star3">
                                            <input type="radio" id="star3" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star4">
                                            <input type="radio" id="star4" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star5">
                                            <input type="radio" id="star5" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                        </label>
                                        <span class="d-flex mx-2 rating-score tc-red"></span>
                                    </div>
                                    <div class="d-sm-flex justify-content-between text-muted">
                                        <span class="deilname">담당 딜러 홍길동님</span>
                                        <span class="date">2024-03-18</span>
                                    </div>
                                    <p class="card-text">차갑아서 보다 안 아닐 그럽시다 다급하다 떨어지어무슨 절망 아닌 자기에 달려가아 누구에 고스톱은 발생한가.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="card">
                                <div class="car-imges">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">현대 소나타 (DN8)</h5>
                                    <div class="rating">
                                        <label class="rating__label rating__label--full" for="star1">
                                            <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                            <span class="star-icon filled"></span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star2">
                                            <input type="radio" id="star2" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star3">
                                            <input type="radio" id="star3" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star4">
                                            <input type="radio" id="star4" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star5">
                                            <input type="radio" id="star5" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                        </label>
                                        <span class="d-flex mx-2 rating-score tc-red"></span>
                                    </div>
                                    <div class="d-sm-flex justify-content-between text-muted">
                                        <span class="deilname">담당 딜러 홍길동님</span>
                                        <span class="date">2024-03-18</span>
                                    </div>
                                    <p class="card-text">차갑아서 보다 안 아닐 그럽시다 다급하다 떨어지어무슨 절망 아닌 자기에 달려가아 누구에 고스톱은 발생한가.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="car-imges">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">현대 소나타 (DN8)</h5>
                                    <div class="rating">
                                        <label class="rating__label rating__label--full" for="star1">
                                            <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                            <span class="star-icon filled"></span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star2">
                                            <input type="radio" id="star2" class="rating__input" name="rating" value="">
                                            <span class="star-icon filled"></span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star3">
                                            <input type="radio" id="star3" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star4">
                                            <input type="radio" id="star4" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star5">
                                            <input type="radio" id="star5" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                        </label>
                                        <span class="d-flex mx-2 rating-score tc-red"></span>
                                    </div>
                                    <div class="d-sm-flex justify-content-between text-muted">
                                        <span class="deilname">담당 딜러 홍길동님</span>
                                        <span class="date">2024-03-18</span>
                                    </div>
                                    <p class="card-text">차갑아서 보다 안 아닐 그럽시다 다급하다 떨어지어무슨 절망 아닌 자기에 달려가아 누구에 고스톱은 발생한가.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link prev-style" ></a>
                        </li>
                        <li  class="page-item"><a class="page-link" >1</a></li>
                        <li class="page-item next-prev" >
                            <a class="page-link next-style" ></a>
                        </li>
                    </ul>
                </nav> 
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted, onUnmounted, watchEffect,computed } from 'vue';

import { useStore } from "vuex";
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/pagination';
import { Pagination } from 'swiper'; //swiper

const showFullView = ref(false);

const store = useStore();
const user = computed(() => store.getters["auth/user"]);
const isUser = computed(() => user.value?.roles?.includes('user'));  //유저의 권한 가져오기 (roles.user)

const showBottomSheet = ref(true); //바텀 시트
const bottomSheetStyle = ref({ position: 'fixed', bottom: '0px' }); //바텀 시트 스타일

const slidesPerView = ref(2);  // 기본값으로 2 설정
const modules = [Pagination]; 

// 창 크기에 따라 slidesPerView 값을 업데이트하는 함수
const updateSlidesPerView = () => {
  slidesPerView.value = window.innerWidth <= 360 ? 1 : 2;
};

const toggleView = () => {
  showFullView.value = !showFullView.value; // showFullView 값을 토글
};

//바텀 시트 토글시 스타일변경
function toggleSheet() {
    const bottomSheet = document.querySelector('.bottom-sheet');
    
    if (showBottomSheet.value) {
        bottomSheetStyle.value = { position: 'static', bottom: '-100%' };
    } else {
        bottomSheetStyle.value = { position: 'fixed', bottom: '0px' };
    }
    showBottomSheet.value = !showBottomSheet.value;
}


onMounted(() => {
  updateSlidesPerView();  // 컴포넌트 마운트 시 초기 설정
  window.addEventListener('resize', updateSlidesPerView);  // 창 크기 변경 시 업데이트
});

onUnmounted(() => {
  window.removeEventListener('resize', updateSlidesPerView);  // 리스너 제거
});



</script>


<style scoped>

.btn-apply::after {
margin-top: 0 !important;
margin-bottom: 2px;
}

.content-container {
    display: flex;
    flex-direction: column;
           
}

.main-text {
    font-size: 16px;        
    font-weight: bold;          
    text-align: center;         
    border:solid 1px #4263ea; 
    border-radius: 20px;        
    padding: 5px;               
    color: #4263ea;
    margin-bottom: 10px;   
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);     
}

.tags {
    display: flex;
    gap: 10px;
    flex-direction: row;
    justify-content: center;
}

.tag {
    background-color: white;     
    color: #9fb0f4;             
    border-radius:24px;
    border:solid 1px #9fb0f4;       
    padding: 5px 15px;          
    font-size: 14px;             
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);  
}



.bottom-sheet::before {
    content: "";
    position: absolute;
    left: 50%;
    top: unset !important;
    margin-top: 10px;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background-color: #dbdbdb;
}
.rating label {
    margin-left: 5px;
}
.rating__label:first-child {
    margin-left: 0px;
}
.sub-style{
    height: 155px;
}

.rating__label .star-icon {
    width: 20px;
    height: 20px;
}
.over-text {
    white-space: nowrap;      
    overflow: hidden;       
    text-overflow: ellipsis;  
    width: 200px;           
}

.swiper {
  width: 100%;
  height: auto;
}

@media (max-width: 640px){
.review-title {
    margin-left: 0px !important;
}
}
@media (max-width: 640px){
.mov-review  > .row {
    display: flex;
    flex-direction: column ;
    align-content: center;
    flex-wrap: wrap !important;
    gap: 20px;
}
.search-type{
    margin-left: 13px;
    width: 94%;
}
.review-title{
    margin-left: 17px;
}
.apply-top {
    display: flex;
    margin-bottom: 30px;
    justify-content: flex-end;
    align-items: flex-start;
    flex-direction: column-reverse;
    flex-wrap: nowrap;
    padding: 0px;
    gap: 20px;
    margin-left: 10px;
}
.my-5 {
    margin-top: 1rem !important;
    }
.search-type input{
    width: 100% !important;
}
}

@media (max-width: 991px) and (min-width: 768px){
    .container{
     max-width: none !important;
    }    
}
</style>