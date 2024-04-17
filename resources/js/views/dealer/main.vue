<template>
    <div class="container">
        <div class="regiest-content">
            <div class="layout-container">
                <!-- 딜러 프로필 요약 정보 -->
                <div class="banner">
                    <div class="styled-div">
                        <div class="profile">
                            <div class="dealer-info">
                                <img src="../../../img/myprofile_ex.png" alt="Profile Image" class="main-profile">
                                <div class="deal-info">
                                    <p class="tc-light-gray">{{ user.dealer.company }} </p>
                                    <p>딜러 <span class="fw-medium">{{ user.dealer.name }}</span>님</p>
                                    <p class="restar">(4.5점)</p>
                                    <p class="no-bidding mt-3"><span>입찰 불가</span></p>
                                </div>
                            </div>
                            <div class="auction-info">
                                <p class="bold-20-font">현재 진행중인 경매가<br><span class="tc-red me-2">0</span>건 있습니다</p>
                                <div style="display: flex;align-items: flex-end;">
                                    <div class="car-image"></div>
                                    <p class="tc-light-gray mb-3">입찰가능 유효시간 2024.03.20</p>
                                </div>
                            </div>
                        </div>
                        <div class="activity-info bold-18-font">
                            <div class="item">
                                <p><span class="tc-red">100</span> 건</p>
                                <p class="interest-icon tc-light-gray normal-16-font">관심</p>
                            </div>
                            <div class="item">
                                <p><span class="tc-red">{{ bidsCountByUser[user.dealer.user_id] || 0 }}</span> 건</p>
                                <p class="bid-icon tc-light-gray normal-16-font">입찰</p>
                            </div>
                            <div class="item">
                                <p><span class="tc-red">6</span> 건</p>
                                <p class="suc-bid-icon tc-light-gray normal-16-font">낙찰</p>
                            </div>
                            <div class="item">
                                <p><span class="tc-red">32</span> 건</p>
                                <p class="purchase-icon tc-light-gray normal-16-font">매입</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="review-content">
                    <div class="apply-top text-start">
                        <h3 class="review-title">공지사항</h3>
                        <a href="/reviewtest" class="btn-apply">전체보기</a>
                    </div>
                    <table class="table custom-border">
                        <tbody>
                            <tr>
                                <td class="tc-light-gray note-date">2024-03-21</td>
                                <td>
                                    <span class="text-with-marker">부정행위 제보 처리 결과 공유 드립니다.</span>
                                </td>
                            </tr>
                            <tr class="tr-recent-new">
                                <td class="tc-light-gray note-date">2024-03-21</td>
                                <td>
                                    <span class="text-with-marker">2024 설 연휴 영업시간 안내</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="tc-light-gray note-date">2024-03-21</td>
                                <td>
                                    <span class="text-with-marker">신규 딜러가입 일시 중단 안내</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="apply-top text-start mt-5">
                        <h3 class="review-title">클레임 현황</h3>
                        <a href="/reviewtest" class="btn-apply">전체보기</a>
                    </div>
                    <table class="table custom-border text-center">
                        <thead class="tr-style">
                            <tr>
                                <th>No.</th>
                                <th>등록일</th>
                                <th>매물번호</th>
                                <th>상태</th>
                                <th>관리</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>24-03-15</td>
                                <td><span class="blue-box list-num">475192</span></td>
                                <td>접수</td>
                                <td class="tc-light-gray">상세</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>24-03-15</td>
                                <td><span class="blue-box list-num">475192</span></td>
                                <td><span class="gray-bl-box">처리중</span></td>
                                <td class="tc-light-gray">상세</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>24-03-15</td>
                                <td><span class="blue-box list-num">475192</span></td>
                                <td><span class="red-box">완료</span></td>
                                <td class="tc-light-gray">상세</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>24-03-15</td>
                                <td><span class="blue-box list-num">475192</span></td>
                                <td><span class="gray-bl-box">처리중</span></td>
                                <td class="tc-light-gray">상세</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="card completed-card border-0">
                    <div class="card-body">
                        <div class="enter-view mt-3">
                            <h5>선택 완료 차량</h5>
                            <router-link :to="{ name: 'dealer.index2'}" class="btn-apply">전체보기</router-link>
                        </div>
                        <span class="tc-light-gray">24시간 내 응대해 주세요!</span>
                        <!-- 차량이 존재 할 경우-->
                        <div v-if="viewBids.length > 0">
                            <div class="complete-car" v-for="bid in viewBids" :key="bid.id">
                                <div class="card my-auction mt-3">
                                    <input class="toggle-heart" type="checkbox">
                                    <label class="heart-toggle"></label>
                                    <div class="card-img-top-placeholder border-rad"></div>
                                    <div class="card-body">
                                        <h5 class="card-title"><span class="blue-box">무사고</span>현대 쏘나타(DN8)</h5>
                                        <div class="enter-view">
                                            <p class="card-text tc-light-gray">12 삼 4567</p>
                                            <a href="#"><span class="red-box-type02 pass-red">자세히 보기</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- 선택 완료된 차량이 없는경우-->
                        <div v-else>
                            <div class="complete-car">
                                <div class="card my-auction mt-3">
                                    <div class="none-complete">
                                        <span>선택 완료된 차량이 없습니다.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
    import {onMounted,computed} from 'vue';
    import useBid from "@/composables/bids";
    import {useStore} from 'vuex';

    const store = useStore();
    const {
        bidsData,
        bidsCountByUser,
        getBids,
        viewBids
    } = useBid();
    const user = computed(() => store.state.auth.user);


    onMounted(async () => {
        await getBids();
    });
</script>
<style>
    .activity-info {
        display: flex;
        justify-content: space-around;
        align-items: center;
        width: 70%;
        height: 100px;
        margin: 19px auto;
        border-radius: 6px;
        background-color: #ffffff;
    }

    .activity-info .item {
        flex: 1;
        text-align: center;
        min-width: 0;
    }

    .activity-info .item:not(:last-child) {
        border-right: 1px solid #cccccc;

    }

    .activity-info p {
        padding: 2px;
    }

    .main-profile {
        width: 120px;
        height: 120px;
        border-radius: 6px;
        object-fit: cover;
        margin-right: 20px;
    }

    .styled-div {
        width: 100%;
        height: 320px;
        margin-top: 57px;
        border-radius: 6px;
        background-color: #f5f5f6;
    }

    .profile {
        display: flex;
        align-items: center;
        justify-content: space-around;
        padding-top: 50px;
        align-items: center;
    }

    .profile img {
        width: 120px;
        height: 120px;
        margin-right: 20px;
        border-radius: 6px;
    }

    .auction-info {
        text-align: end;
    }

    .dealer-info {
        display: flex;
    }

    .restar::before {
        content: "";
        display: inline-block;
        width: 16px;
        height: 14px;
        background-image: url('../../../img/icon-profile-star.png');
        background-repeat: no-repeat;
        background-size: contain;
        background-position: center;
        margin-right: 5px;
    }

    .deal-info>p:not(:first-child) {
        margin-top: 5px;
    }

    .car-image {
        background-image: url('../../../img/car-objects.png');
        width: 92px;
        height: 92px;
        background-repeat: no-repeat;
        background-size: contain;
        background-position: center;
    }

    .interest-icon::before {
        content: "";
        display: inline-block;
        width: 16px;
        height: 13px;
        background-image: url('../../../img/interest-icon.png');
        background-repeat: no-repeat;
        background-size: contain;
        background-position: center;
        margin-right: 5px;
    }

    .purchase-icon::before {
        content: "";
        display: inline-block;
        width: 16px;
        height: 13px;
        background-image: url('../../../img/purchase-icon.png');
        background-repeat: no-repeat;
        background-size: contain;
        background-position: center;
        margin-right: 5px;
    }

    .suc-bid-icon::before {
        content: "";
        display: inline-block;
        width: 16px;
        height: 13px;
        background-image: url('../../../img/suc-bid-icon.png');
        background-repeat: no-repeat;
        background-size: contain;
        background-position: center;
        margin-right: 5px;
    }

    .bid-icon::before {
        content: "";
        display: inline-block;
        width: 16px;
        height: 13px;
        background-image: url('../../../img/Icon-gray-tag.png');
        background-repeat: no-repeat;
        background-size: contain;
        background-position: center;
        margin-right: 5px;
    }

    .text-with-marker {
        position: relative;
        display: inline-flex;
        align-items: center;
        padding-right: 20px;
    }

    .text-with-marker::after {
        content: "";
        position: absolute;
        right: 13px;
        top: 2px;
        transform: translate(100%, -50%);
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: #f24200;
    }

    .note-date {
        width: 15%;
    }

    .tr-style th {
        border-top: none !important;
        border-radius: 6px;
        background-color: #f5f5f678;
    }

    .list-num {
        margin-right: 0px !important;
    }

    .process {
        color: black !important;
    }

    .complete-car {
        display: flex;
        flex-direction: column;
    }

    .completed-card {
        border-radius: 20px;
        padding: 20px;
    }

    .completed-card::before {
        content: "";
        position: absolute;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background-color: #dbdbdb;
    }

    .enter-view {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .none-complete {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        height: 210px;
        background-color: #f5f5f6;
    }

    .none-complete span {
        position: relative;
        z-index: 2;
    }

    .none-complete span::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 91px;
        height: 91px;
        background-image: url('../../../img/electric-car.png');
        background-size: contain;
        background-repeat: no-repeat;
        z-index: 1;
    }

    .pass-red::after {
        content: "";
        display: inline-block;
        height: 9px;
        inline-size: 13px;
        background-image: url('../../../img/Icon-red-right.png');
        background-repeat: no-repeat;
        background-size: contain;
        background-position: center;
    }

    .border-rad {
        border-radius: 6px;
    }
</style>