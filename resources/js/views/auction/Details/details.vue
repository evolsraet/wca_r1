<template>
    <div class="container">
        <!--차량 정보 조회 내용 : 제조사,최초등록일,배기량, 추가적으로 용도변경이력 튜닝이력 리콜이력 추가 필요-->
            <div class="container my-4">
                <div class="mb-4">
                    <div class="card my-auction">
                        <input class="toggle-heart" type="checkbox" checked />
                        <label class="heart-toggle"></label>
                        <div class="card-img-top-ty01"></div>
                        <div class="card-body">
                            <div class="enter-view align-items-baseline ">
                                <h5 class="card-title"><span class="blue-box">무사고</span>12 삼 4567</h5>
                                <h5 class="card-title my-0">현대 쏘나타(DN8)</h5>
                            </div>
                            <div class="enter-view">
                                <p class="card-text tc-light-gray">12 삼 4567</p>
                            </div>
                        </div>
                        <div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <p class="auction-deadline normal-16-font">경매 마감일<span> 2024년 2월 13일 오후 6시 00분</span></p>
    <div class="container card-style">
    <div class="card card-custom">
        <div class="row">
        <div class="col-6">
            <div class="item-label">년식</div>
            <div class="item-value">20년형</div>
        </div>
        <div class="col-6">
            <div class="item-label">주행거리</div>
            <div class="item-value">103,000km</div>
        </div>
        </div>
        <div class="row">
        <div class="col-6">
            <div class="item-label">내사 피해</div>
            <div class="item-value">1건</div>
        </div>
        <div class="col-6">
            <div class="item-label">타사 피해</div>
            <div class="item-value">3건</div>
        </div>
        </div>
    </div>
    </div>
        <div class="container p-4">
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
            <ul class="machine-inform-title">
                <li class="tc-light-gray">옵션정보</li>
            </ul>
            <div></div>
            <ul class="machine-inform-title">
                <li class="tc-light-gray">추가옵션</li>
                <li class="info-num">-</li>
            </ul>
        </div>
        <div class="contour-style"></div>
        <div class="container">
            <div class="styled-div">

            </div>
        </div>
</template>

<script setup>
    import {
        ref
    } from 'vue';

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
    .auction-deadline {
    width: 100%;
    height: 38px;
    background-color: #f5f5f6;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: space-around;
    gap: 5px;
}
.card-style{
    padding-top: 1.5rem;
    padding-right: 1.5rem;
    padding-left: 1.5rem;
}
.card-style .card:hover{
    box-shadow: 0 0 6px 0 rgba(27, 50, 142, 0.2);
}

.card-style .card-custom {
      margin: 1rem;
      padding: 1rem;
      border-radius: 5px;
      text-align: center;
    }
    .card-style .card-custom :hover{
        box-shadow: none !important;
    }
    .card-style .item-label {
      font-size: 0.85rem;
      color: #6c757d;
      margin-bottom: 0.5rem;
    }
    .card-style .item-value {
      font-size: 1.25rem;
      color: #212529;
      margin-bottom: 1rem; 
    }
    .card-style .row:not(:last-child) {
      padding-bottom: 1rem;
    }
    .card-style .row:not(:first-child) {
      padding-top: 1rem; 
    }
    .card-style .col-6 {
      position: relative;
    }
    .card-style .col-6:not(:last-child)::after {
        content: "";
        position: absolute;
        right: 0;
        top: 50%; 
        bottom: 50%; 
        height: 50%;
        width: 1px;
        background-color: #dee2e6;
        transform: translateY(-50%);
}

.contour-style {
    height: 6px;
    background-color: #d9d9d9;
}
.styled-div {
    width: 100%;
    height: 200px;

    border-radius: 6px;
    background-color: #f5f5f6;
}
</style>
