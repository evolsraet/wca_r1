<template>
    <div class="container">
        <div class="main-contenter">
            <div class="review">
                <div class="review-content mov-review my-5">
                    <div class="proceeding"></div>
                    <h3 class="review-title">이용후기 관리</h3>
                    <div class="tab-nav my-4">
                        <ul>
                            <li class="col-4"><a href="/mngreivew" class="active" title="작성 가능한 이용후기">작성한 이용후기(3)</a></li>
                            <li class="col-4"><a href="/mngreivewud" title="작성한 이용후기">작성 가능한 이용 후기(2)</a></li>
                        </ul>
                        <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                    </div>
                <!-- 작성한 이용후기-->  
                    <div class="row row-cols-1 row-cols-md-4 g-3">
                        <div class="col" v-for="review in reviews" :key="review.id">
                            <div class="card">
                                <div class="car-imges">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ review.title }}</h5>
                                    <div class="rating">
                                        <div v-for="index in 5" :key="index" class="star" :class="{ 'filled-star': index <= review.rating, 'empty-star': index > review.rating }"></div>
                                    </div>
                                    <div class="d-sm-flex justify-content-between text-muted">
                                        <span class="deilname">담당 딜러 {{ review.dealer }}</span>
                                        <span class="date">{{ review.date }}</span>
                                    </div>
                                    <p class="card-text">{{ review.content }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- 작성 가능한 이용후기-->  
                    <div class="row row-cols-1 row-cols-md-4 g-3">
                        <div class="col" v-for="review in reviews" :key="review.id">
                            <div class="card">
                                <div class="car-imges">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ review.title }}</h5>
                                    <div class="rating">
                                        <div v-for="index in 5" :key="index" class="star" :class="{ 'filled-star': index <= review.rating, 'empty-star': index > review.rating }"></div>
                                    </div>
                                    <div class="d-sm-flex justify-content-between text-muted">
                                        <span class="deilname">담당 딜러 {{ review.dealer }}</span>
                                        <span class="date">{{ review.date }}</span>
                                    </div>
                                    <p class="card-text">{{ review.content }}</p>
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
    import {
        ref
    } from 'vue';

    const baseReview = {
        id: 1,
        image: 'path/to/image1.jpg',
        title: '현대 소나타 (DN8)',
        rating: 4,
        dealer: '홍길동님',
        date: '2024-03-18',
        content: '차갑아서 보다 안 아닐 그럽시다 다급하다 떨어지어무슨 절망 아닌 자기에 달려가아 누구에 고스톱은 발생한가.'
    };

    const createReviews = (baseReview, count) => {
        return Array.from({
            length: count
        }, (_, index) => ({
            ...baseReview,
            id: baseReview.id + index,
            image: `path/to/image${index + 1}.jpg`,
            title: `${baseReview.title} #${index + 1}`,
        }));
    };

    const reviews = ref(createReviews(baseReview, 12));
</script>
<style>
    .review {
        margin-top: 60px;
    }

    .tab-nav ul li a {
        display: block;
        position: relative;
        width: 100%;
        padding: 10px 77px;
        font-size: 18px;
        color: #afafaf;
        white-space: nowrap;
    }

    .tab-nav ul li a.active::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 2px;
        background-color: #ff4b4b;
    }

    .tab-nav ul li a.active {
        font-weight: bold;
        color: #041231;
    }

    .tab-nav {
        border-bottom: 1px solid #afafaf;
        margin-bottom: 40px;
    }

    .tab-nav ul li {
        display: table-cell;
        text-align: center;
    }

    .col-4 {
        width: 25% !important;
    }

    .search-type {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-type input {
        background-color: #f5f5f5;
        margin-top: 0;
        padding: 0 40px 0 20px;
        height: 42px;
        line-height: 40px;
        border-radius: 26px;
        width: 300px;
        border: none;
        outline: none;
    }

    .search-type .search-btn {
        cursor: pointer;
        position: absolute;
        right: 5px;
        top: 1px;
        width: 40px;
        height: 40px;
        border: none;
        background-color: transparent;
        background-image: url('../../img/search.png');
        background-repeat: no-repeat;
        background-position: center;
        background-size: 20px 20px;
        font-size: 0;
    }

    @media (max-width: 760px) {
        .tab-nav ul li a {
            padding: 10px 10px;
            font-size: 14px;
        }

    }

    @media (max-width: 640px) {
        .mov-review>.row {
            display: flex;
            flex-direction: column;
            align-content: center;
            flex-wrap: wrap !important;
            gap: 20px;
        }

        .search-type {
            margin-left: 13px;
            width: 94%;
        }

        .review-title {
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

        .search-type input {
            width: 100% !important;
        }

        .review {
            margin-top: 10px;
        }
    }
</style>