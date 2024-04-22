<template>
    <div class="container">
        <!--차량 정보 조회 내용 : 제조사,최초등록일,배기량, 추가적으로 용도변경이력 튜닝이력 리콜이력 추가 필요-->
        <div class="main-contenter mt-3">
            <h5>차량 정보 조회 되었어요</h5>
            <ul class="machine-inform-title">
                <li class="tc-light-gray">차량번호</li>
                <li class="info-num">12 삼 4567</li>
                <li class="car-icon"></li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">제조사</li>
                <li class="sub-title">현대</li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">모델</li>
                <li class="sub-title">쏘나타</li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">세부모델</li>
                <li class="sub-title">쏘나타 하이브리드 (DN8)</li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">등급</li>
                <li class="sub-title">프리미엄</li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">세부등급</li>
                <li class="sub-title">패밀리</li>
            </ul>
            <ul class="machine-inform-title">
                <li class="tc-light-gray">최초등록일</li>
                <li class="info-num">2022년 2월 3일</li>
                <li class="car-aside-icon"></li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">년식</li>
                <li class="sub-title">20년형</li>
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
                <li class="sub-title">가솔린+전기</li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">미션</li>
                <li class="sub-title">오토미션</li>
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
                    <span class="tc-red bold-18-font">- 만원</span>
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
import { ref } from 'vue';

const isActive = ref(false); 

function toggleDetailContent() {
    isActive.value = !isActive.value;  
}
</script>
