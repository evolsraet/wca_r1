@php
    $check = isset($check) ? $check : true;
    $isTitle = isset($isTitle) ? $isTitle : true;
    $isLabel = isset($isLabel) ? $isLabel : true;
    $size = isset($size) ? $size : 40;
    $gap = isset($gap) ? $gap : 3;
    $alignStart = isset($alignStart) ? $alignStart : 'center';
@endphp
<div class="mb-4 text-center" x-data="{
    dealerId: 0,
    rating: 0,
    check: {{ $check ? 'true' : 'false' }},
    labels: ['별로예요', '괜찮아요', '좋아요', '만족해요', '최고예요!'],
    init: function() {

        if(article) {
            this.rating = parseInt(article.extra3) || 0;
            this.dealerId = article.extra2 || 0;
        }

        $watch('article', value => {
            this.rating = parseInt(value.extra3) || 0;
            this.dealerId = value.extra2 || 0;
        });
    },
    selectRating(value) {
        if (!this.check) return;
        this.rating = value;

        // extra2에 dealer_id 저장
        const dealerInput = document.querySelector('input[name=\'article.extra2\']');
        if (dealerInput) {
            dealerInput.value = this.dealerId;
        }

        // extra3에 rating 저장
        const ratingInput = document.querySelector('input[name=\'article.extra3\']');
        if (ratingInput) {
            ratingInput.value = this.rating;
        }
    }
}">
    @if($isTitle)
        <h5 class="fw-bold mb-3">거래는 어떠셨나요?</h5>
    @endif
    <div class="d-flex justify-content-{{ $alignStart }} gap-{{ $gap }}">
        <template x-for="i in 5">
            <div class="text-center cursor-pointer" @click="selectRating(i)">
                <svg :class="rating >= i ? 'text-danger' : 'text-secondary'" xmlns="http://www.w3.org/2000/svg" width="{{ $size }}" height="{{ $size }}" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.32-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.63.283.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                </svg>
                @if($isLabel)
                <div class="mt-1 text-sm" x-text="labels[i - 1]"></div>
                @endif
            </div>
        </template>
    </div>
    @if($isLabel)
    <template x-if="rating > 0">
        <div class="mt-2 text-danger fw-bold" x-text="rating + '점'"></div>
    </template>
    @endif
    <input type="hidden" name="article.extra2" value="">
    <input type="hidden" name="article.extra3" value="">
</div>