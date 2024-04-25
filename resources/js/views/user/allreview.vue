<template>
    <div class="container">
        <div class="main-contenter">
            <div class="demo"></div>
            <div class="review">
                <div class="review-content mov-review my-5">
                    <div class="apply-top text-start">
                        <h3 class="review-title">다른 사람들의 이용후기에요</h3>
                        <div class="search-type">
                            <input type="text" placeholder="모델명,차량번호,지역">
                            <button type="button" class="search-btn">검색</button>
                        </div>
                    </div>
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
import { ref } from 'vue';


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
    return Array.from({ length: count }, (_, index) => ({
        ...baseReview,
        id: baseReview.id + index,
        image: `path/to/image${index + 1}.jpg`,
        title: `${baseReview.title} #${index + 1}`,
    }));
};

const reviews = ref(createReviews(baseReview, 12));
</script>
<style>
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
    background-image: url('../../../img/search.png');
    background-repeat: no-repeat;
    background-position: center; 
    background-size: 20px 20px;
    font-size: 0; 
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
</style>