<template>
    <div class="container">
        <div class="main-contenter mt-3">
            <h5>차량 정보 조회 되었어요</h5>
            <ul class="machine-inform-title">
                <li class="tc-light-gray">차량번호</li>
                <li class="info-num">{{ carDetails.no }}</li>
                <li class="car-icon"></li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">제조사</li>
                <li class="sub-title"></li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">모델</li>
                <li class="sub-title">{{ carDetails.model }}</li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">세부모델</li>
                <li class="sub-title">{{ carDetails.modelSub }}</li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">등급</li>
                <li class="sub-title">{{ carDetails.grade }}</li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">세부등급</li>
                <li class="sub-title">{{ carDetails.gradeSub }}</li>
            </ul>
            <ul class="machine-inform-title">
                <li class="tc-light-gray">최초등록일</li>
                <li class="info-num"></li>
                <li class="car-aside-icon"></li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">년식</li>
                <li class="sub-title">{{ carDetails.year }}</li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">차량유형</li>
                <li class="sub-title">종합 승용차</li>
            </ul>
            <ul class="machine-inform-title">
                <li class="tc-light-gray">배기량</li>
                <li class="info-num">2000cc</li>
                <li class="gasoline-icon"></li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">연료</li>
                <li class="sub-title">{{ carDetails.fuel }}</li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">미션</li>
                <li class="sub-title">{{ carDetails.mission }}</li>
            </ul>
            <ul class="machine-inform-title">
                <li class="tc-light-gray">용도변경이력</li>
                <li class="info-num">-</li>
                <li class="clean-icon"></li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">튜닝이력</li>
                <li class="sub-title">1회</li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">리콜이력</li>
                <li class="sub-title">-</li>
            </ul>
        </div>
        <div class="detail-content mt-5" :class="{ 'active': isActive }" @click="toggleDetailContent">
                <div class="top-content wd-100">
                    <p class="tc-light-gray bold-18-font">현재 시세 <span class="normal-14-font">(무사고 기준)</span></p>
                    <span class="tc-red bold-18-font">{{ carDetails.priceNow }} 만원</span>
                </div>
                <div class="middle">
                <p>차량 정보가 다르신가요?<span class="tooltip-toggle nomal-14-font" aria-label="일 1회 갱신 가능합니다, 갱신한 정보는 1주간 보관됩니다" tabindex="0"></span></p>
                <router-link :to="{ name: 'autction.index' }" class="tc-red link">정보갱신하기</router-link>
                </div>
                <div class="none-info">
                <div class="complete-car">
                        <div class="card my-auction mt-3">
                            <div class="none-complete-ty02">
                                <span class="tc-light-gray">일치하는 차량이 없습니다.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end my-5">
                 <router-link :to="{ path: '/selldt' }" class="btn primary-btn ">경매 신청하기</router-link></div>
            </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { computed } from 'vue';
import { useRoute } from 'vue-router';

const isActive = ref(false); 
const route = useRoute();
const carDetails = ref({});

onMounted(() => {
  const storedData = localStorage.getItem('carDetails');
  if (storedData) {
    carDetails.value = JSON.parse(storedData);
  } else {
    console.log('No car details found in local storage.');
  }
});

function toggleDetailContent() {
    isActive.value = !isActive.value;  
}


</script>

<style>
.tooltip-toggle {
    cursor: pointer;
    position: relative;
    width: 20px;
    height: 20px;
    background-image: url('../../../../img/tooltip-toggle.png');
    background-repeat: no-repeat;
    background-size: contain;
    background-position: center;
    display: inline-block;
    margin-left: 10px;
    vertical-align: text-bottom;



  &::before,
  &::after {
    position: absolute;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.5s ease;
  }

  &::before {
    content: attr(aria-label);
    top: -77px;
    left: 80px;
    transform: translateX(-50%);
    background-color: #fffcd4;
    color: #ffcb00;
    text-align: center;
    border-radius: 6px;
    border: 1px solid #ffcb00;
    padding: 8px;
    width: auto;
    min-width: 210px;
}

  &::after {
    content: '';
    top: -16px;
    left: 50%;
    transform: translateX(-50%);
    border-left: 6px solid transparent;
    border-right: 6px solid transparent;
    border-top: 8px solid #fee585;
  }

  &:hover::before,
  &:hover::after,
  &:focus::before,
  &:focus::after {
    opacity: 1; 
  }
}




.link{
    text-decoration : underline !important;
}
.none-complete-ty02 span::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 91px;
    height: 91px;
    background-image: url('../../../../img/electric-car.png');
    background-size: contain;
    background-repeat: no-repeat;
    z-index: 1;
}
.none-complete-ty02 {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    height: 160px;
    background-color: #f5f5f6;
}
.detail-content.active {
    bottom: 0px;
}
.middle{
    display: flex;
    margin-top: 35px;
    justify-content: space-between;
}
.detail-content .top-content{
    display: flex;
    margin-top: 35px;
    height: 50px;
    background-color: #ebedf1;
    border-radius: 6px;
    justify-content: space-between;
    padding: 13px;
}

.detail-content {
    background-color: #f8f9fa;
    box-shadow: 0px -7px 16px 0px rgba(212, 212, 212, 0.53);
    border-top-right-radius: 30px;
    border-top-left-radius: 30px;
    position: absolute;
    width: 100%;
    bottom: -190px;
    left: 0;
    transition: 0.5s;
    padding: 5px 20px;
    box-sizing: border-box;
    top: auto; 
}
.detail-content::before {
    content: "";
    position: absolute;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background-color: #dbdbdb;
}
.detail-content:hover {
    bottom: 0px;
}

@media (max-width: 400px) {
    .detail-content {
        top: 640px;
        
    }
    
    .detail-content.active {
        top: calc(640px - 250px);
    }
}


@media (min-width: 401px) and (max-width: 768px) {
    .detail-content {
        top: 640px;
    }
    
    .detail-content.active {
        top: calc(640px - 250px);
        height: auto;
    }
}

@media (min-width: 769px) and (max-width: 1024px) {
    .detail-content {
        top: 600px;
    }
    
    .detail-content.active {
        top: calc(600px - 250px);
        height: auto;
    }
}

@media (min-width: 1025px) {
    .detail-content {
        top: 600px;
    }
    
    .detail-content.active {
        top: calc(600px - 250px);
        height: auto;
    }
}


.machine-inform-title {
    list-style: none; 
    padding: 0; 
    margin-top: 30px;
    display: flex;
    justify-content: flex-start;
}
.machine-inform {
    list-style: none; 
    padding: 0; 
    margin-top: 10px;
    display: flex;
    justify-content: flex-start;
}
.sub-title {
    margin-right: 20px;
}
.machine-inform-title li, .machine-inform li {
    flex: 1;
}

.machine-inform-title li.clean-icon{
    flex: 0 0 30px;
    height: 20px;
    background-image: url('../../../../img/clean.png');
    background-repeat: no-repeat;
    background-size: contain;
    background-position: center;
    background-color: #fff;
    margin-left: auto; 
}

.machine-inform-title li.gasoline-icon{
    flex: 0 0 30px;
    height: 20px;
    background-image: url('../../../../img/gasoline-pump.png');
    background-repeat: no-repeat;
    background-size: contain;
    background-position: center;
    background-color: #fff;
    margin-left: auto; 
}



.machine-inform-title li.car-aside-icon{
    flex: 0 0 30px;
    height: 20px;
    background-image: url('../../../../img/Icon-block-car-side.png');
    background-repeat: no-repeat;
    background-size: contain;
    background-position: center;
    background-color: #fff;
    margin-left: auto; 
}




.machine-inform-title li.car-icon{
    flex: 0 0 30px;
    height: 20px;
    background-image: url('../../../../img/Icon-black-car.png');
    background-repeat: no-repeat;
    background-size: contain;
    background-position: center;
    background-color: #fff;
    margin-left: auto; 
}




.machine-inform-title li.info-num, .machine-inform li.sub-title {
    flex: 1.5; 
}

</style>