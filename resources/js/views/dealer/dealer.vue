<template>
    <div class="container">
        <div class="main-contenter">
            <div class="review">
                <div class="mov-review my-5">
                    <div class="apply-top text-start">
                        <div class="search-type2">
                            <h3 class="review-title">선택 완료 차량<br><span class="mb-2 tc-light-gray">24시간 내 응대해 주세요!</span></h3>
                            <input type="text" placeholder="모델명,차량번호,지역">
                            <button type="button" class="search-btn">검색</button>
                        </div>
                    </div>
                    <div class="registration-content">
                        <div class="text-start"></div>
                        <div class="text-end pd-10">
                            <select class="form-select select-rank" aria-label="최근 등록 순">
                                <option selected>최근 등록 순</option>
                                <option value="1">가격 낮은 순</option>
                                <option value="2">가격 높은 순</option>
                                <option value="3">연식 오래된 순</option>
                                <option value="4">연식 최신 순</option>
                            </select>
                        </div>
                    </div>
                    <div class="container my-5">
                    <div class="row">
                        <div class="col-md-3 mb-4" v-for="bid in bids" :key="bid.id">
                            <div class="card my-auction">
                                <input class="toggle-heart" type="checkbox" checked/>
                                    <label class="heart-toggle" ></label>
                                    <div class="selection-complete-img"></div> 
                                    <div class="wait-selection">입찰가 {{ bid.price }}</div>
                                    <div class="card-body">
                                    <h5 class="card-title"><span class="blue-box">무사고</span>12 삼 4567</h5>
                                    <p class="card-text tc-light-gray">현대 쏘나타(DN8) </p>
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
import { ref, onMounted } from 'vue';
import useAuth from "@/composables/auth";

const { getBids } = useAuth();
const bids = ref([]); 

onMounted(async () => {
    bids.value = await getBids();
});
</script>


<style>
.pd-10{
    padding: 10px;
}
.search-type2 span{
    font-size: 17px;
}
</style>
