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
                    <div :class="rowClass">
                        <div class="col">
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
                        <div class="col">
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
                        <div class="col">
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
                        <div class="col">
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
                        <div class="col">
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
            </div>
            <!-- Pagination -->
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
</template>
<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const rowClass = ref('row row-cols-1');

const updateRowClass = () => {
  if (window.innerWidth <= 1200) {
    rowClass.value = 'row row-cols-1 row-cols-md-3 g-3';
  } else {
    rowClass.value = 'row row-cols-1 row-cols-md-4 g-3';
  }
};

onMounted(() => {
  updateRowClass();
  window.addEventListener('resize', updateRowClass);
});

onUnmounted(() => {
  window.removeEventListener('resize', updateRowClass);
});
</script>

<style scoped>
.rating__label .star-icon {
    width: 30px;
    height: 30px;
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