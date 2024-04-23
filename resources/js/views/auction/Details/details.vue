<template>
    <div class="container">
        <!--차량 정보 조회 내용 : 제조사,최초등록일,배기량, 추가적으로 용도변경이력 튜닝이력 리콜이력 추가 필요-->
        <div class="main-contenter mt-3">
            <div class="container my-4">
                        <div class="mb-4">
                            <div class="card my-auction">
                                <input class="toggle-heart" type="checkbox" checked />
                                <label class="heart-toggle"></label>
                                <div class="card-img-top-ty01"></div>
                                    <div class="card-body">
                                        <div class="enter-view align-items-center ">
                                        <h5 class="card-title"><span class="blue-box">무사고</span>12 삼 4567</h5>
                                        <h5 class="card-title my-0">현대 쏘나타(DN8)</h5>
                                    </div>
                                    <div class="enter-view">
                                        <p class="card-text tc-light-gray">12 삼 4567</p>
                                    </div>
                                </div>
                                <div class="" style="width: 100%; height: 40px; background-color: #f7f7f7;">
                                    <p> 경매 마감일 2024년 2월 13일 오후 6시 00분</p>
                                </div>
                                <div style="width: 100%; height: 200px;">
                                    <P>년차</P>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="main-contenter mt-3">
                        <ul class="machine-inform-title">
                            <li class="tc-light-gray">차량번호</li>
                            <li class="info-num"></li>
                            <li class="car-icon"></li>
                        </ul>
                        <ul class="machine-inform">
                            <li class="tc-light-gray">제조사</li>
                            <li class="sub-title"></li>
                        </ul>
                        <ul class="machine-inform">
                            <li class="tc-light-gray">모델</li>
                            <li class="sub-title"></li>
                        </ul>
                        <ul class="machine-inform">
                            <li class="tc-light-gray">세부모델</li>
                            <li class="sub-title"></li>
                        </ul>
                        <ul class="machine-inform">
                            <li class="tc-light-gray">등급</li>
                            <li class="sub-title"></li>
                        </ul>
                        <ul class="machine-inform">
                            <li class="tc-light-gray">세부등급</li>
                            <li class="sub-title"></li>
                        </ul>
                        <ul class="machine-inform-title">
                            <li class="tc-light-gray">최초등록일</li>
                            <li class="info-num"></li>
                            <li class="car-aside-icon"></li>
                        </ul>
                        <ul class="machine-inform">
                            <li class="tc-light-gray">년식</li>
                            <li class="sub-title"></li>
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
                            <li class="sub-title"></li>
                        </ul>
                        <ul class="machine-inform">
                            <li class="tc-light-gray">미션</li>
                            <li class="sub-title"></li>
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
<style>
.card-img-top-ty01 {
    width: 100%;
    height: 160px;
    background-image: url('../../../../img/car_example.png');
    background-size: cover;
    background-position: center;
    border-radius: 6px;
}

</style>