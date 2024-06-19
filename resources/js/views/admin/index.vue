<template>
 <div class="container mt-auto mb-auto" id="dashadmin">
        <div class="card-container">
            <div class="section">
                <h5 class="text-start">회원 491</h5>
                <div class="card">
                    <div class="number d-flex justify-content-around">심사중 <span class="tc-red">31</span></div>
                    <div class="number d-flex justify-content-around">정상 <span>363</span></div>
                    <div class="divider"></div>
                    <div class="d-flex justify-content-around tc-light-gray">탈퇴 <span>12</span></div>
                    <div class="d-flex justify-content-around tc-light-gray">가입거부 <span>3</span></div>
                </div>
            </div>
            <div class="section">
                <h5 class="text-start">입금 491</h5>
                <div class="card">
                    <div class="number d-flex justify-content-around">입금 대기 <span class="tc-red">31</span></div>
                    <div class="divider"></div>
                    <div class="d-flex justify-content-around tc-light-gray">입금 완료 <span>363</span></div>
                </div>
            </div>
            <div class="section">
                <h5 class="text-start">매물 491</h5>
                <div class="card">
                    <div class="number d-flex justify-content-around">진단중 <span class="tc-red">31</span></div>
                    <div class="divider"></div>
                    <div class="d-flex justify-content-around tc-light-gray">진단완료 <span>363</span></div>
                </div>
            </div>
            <div class="section">
                <h5 class="text-start">후기 491</h5>
                <div class="card">
                    <div class="number d-flex justify-content-around">이용후기 <span class="tc-red">316</span></div>
                    <div class="divider"></div>
                    <div class="d-flex justify-content-around tc-light-gray">비활성화 <span>33</span></div>
                </div>
            </div>
        </div>
        <div class="mt-5 p-3">
            <h4>공지사항</h4>
            <div class="tab-nav my-4">
                <ul>
                    <li class="col-2">
                        <a href="#" @click.prevent="setActiveTab('available')" :class="{ 'active': activeTab === 'available' }" title="공통">공통</a>
                    </li>
               <!--     <li class="col-2">
                        <a href="#" @click.prevent="setActiveTab('written')" :class="{ 'active': activeTab === 'written' }" class="ms-3" title="일반">일반</a>
                    </li>
                    <li class="col-2">
                        <a href="#" @click.prevent="setActiveTab('written')" :class="{ 'active': activeTab === 'written' }" class="ms-3" title="딜러">딜러</a>
                    </li>-->
                </ul>
                <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
            </div>
            <div class="mt-5">
            <table v-if="activeTab === 'available'">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Content</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="date">22.06.13</td>
                        <td class="content">제외하려 별 등기가 것 술에 즉 있어서, 뜨는 숲을 것 직무가 자신은 생각을 싫다.⋯</td>
                        <td class="actions">
                            <a href="#" class="edit">수정</a> |
                            <a href="#" class="delete">삭제</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="date">22.06.13</td>
                        <td class="content">제외하려 별 등기가 것 술에 즉 있어서, 뜨는 숲을 것 직무가 자신은 생각을 싫다.⋯</td>
                        <td class="actions">
                            <a href="#" class="edit">수정</a> |
                            <a href="#" class="delete">삭제</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="date">22.06.13</td>
                        <td class="content">제외하려 별 등기가 것 술에 즉 있어서, 뜨는 숲을 것 직무가 자신은 생각을 싫다.⋯</td>
                        <td class="actions">
                            <a href="#" class="edit">수정</a> |
                            <a href="#" class="delete">삭제</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

        <!-- 가장 최신의 판매차량 이용후기 -->
       <!-- <div class="">
            <div class="review">
                <div class="review-content mov-review my-5">
                    <div v-if="activeTab === 'available'" class="row">
                        <div class="col-md-6 p-2 mb-2">
                            <p class="mx-2">2024.03.18</p>
                            <div class="p-2 card my-auction">
                                <div class="review-image">
                                    <p class="review-date"></p>
                                    <div class="card-img-top-placeholder"></div>
                                </div>
                                <div class="card-body">
                                    <div class="mx-2 enter-view align-items-baseline mt-1 bold-18-font">  
                                        <h5>더 뉴 그랜저 IG 2.5 가솔린 르블랑</h5>
                                    </div>
                                    <div class="tc-light-gray mx-2">
                                        <p> 년 / 2.4km / 무사고</p>
                                        <p>현대 쏘나타 (DN8)</p>
                                        <p>매물번호 / 564514</p>
                                        <p>딜 러 명 / </p>
                                    </div>
                                    <p class="auction-deadline tc-light-gray">판매가<span>12000만원</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div v-if="activeTab === 'written'" class="row">
                        <div class="col-md-6 p-2 mb-2">
                            <div class="card my-auction p-2">
                                <div class="review-image02">
                                    <p class="review-date"></p>
                                    <div class="card-img-top-placeholder mt-2"></div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">더 뉴 그랜저 IG 2.5 가솔린 르블랑</h5>
                                    <p>2020년 / 2.4km / 무사고</p>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <span class="blue-box">보험 3건</span><span class="gray-box">재경매</span>
                                        </div>
                                    </div>
                                    <div class="mb-2 justify-content-between flex align-items-center bold-18-font"></div>
                                    <div class="rating">
                                        <label class="rating__label rating__label--full">
                                            <input type="radio" class="rating__input" name="rating" value="1">
                                            <span class="star-icon"></span>
                                        </label>
                                        <label class="rating__label rating__label--full">
                                            <input type="radio" class="rating__input" name="rating" value="2">
                                            <span class="star-icon"></span>
                                        </label>
                                        <label class="rating__label rating__label--full">
                                            <input type="radio" class="rating__input" name="rating" value="3">
                                            <span class="star-icon"></span>
                                        </label>
                                        <label class="rating__label rating__label--full">
                                            <input type="radio" class="rating__input" name="rating" value="4">
                                            <span class="star-icon"></span>
                                        </label>
                                        <label class="rating__label rating__label--full">
                                            <input type="radio" class="rating__input" name="rating" value="5">
                                            <span class="star-icon"></span>
                                        </label>
                                    </div>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>-->
</div>
</template>
<script setup>
import { ref } from 'vue';

const activeTab = ref('available');

function setActiveTab(tab) {
    activeTab.value = tab;
}
</script>
