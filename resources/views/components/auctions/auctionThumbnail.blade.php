@php
    $auction = $auction ?? null;
    $user = auth()->user();

    $hashid = request()->route('id');
    
    if ($hashid && !ctype_digit($hashid)) {
        $decoded = Hashids::decode($hashid);
        $id = $decoded[0] ?? null;
    } else {
        $id = null; // 또는 필요 시 $id = (int) $hashid;
    }
    
@endphp

<script>
    let car_thumbnail = `{!! $auction->car_thumbnail !!}`;
    window.car_thumbnail = car_thumbnail;
</script>

<div x-data='auctionThumbnail()'>

    @if($isUser)

        <div class="auction-thumbnail carousel-wrapper mb-3 position-relative animate__animated animate__fadeIn" style="">

            {{-- 경매상태 뱃지 --}}
            <div class="position-absolute top-0 start-0 m-3 z-2" >
                <x-auctions.auctionStatusBadges />
            </div>

            <x-auctions.auctionLikeButton />

            @if ($status !== 'ask' && $status !== 'diag')
                <div id="customCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <template x-for="(img, index) in car_thumbnail" :key="index">
                            <div :class="`carousel-item ${index === 0 ? 'active' : ''}`">
                                <img :src="img" class="d-block w-100" alt="썸네일 이미지">
                            </div>
                        </template>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#customCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#customCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>

                {{-- 썸네일 하단 표시 --}}
                <div 
                    class="carousel-thumbnails w-100 mt-2"
                    x-show="car_thumbnail.length > 1"
                >
                    <template x-for="(img, index) in car_thumbnail.slice(0, 8)" :key="index">
                        <img 
                            :src="img"
                            class="thumb-img"
                            :data-bs-target="'#customCarousel'"
                            :data-bs-slide-to="index"
                            :aria-label="'Slide ' + (index + 1)"
                            :aria-current="index === 0 ? '' : undefined"
                        >
                    </template>
                </div>
            @else

                <div class="text-center auction-thumbnail-empty py-5 px-3 rounded position-relative">
                    {{-- 썸네일 이미지 (빈 썸네일 아이콘) --}}
                    <div class="my-4">
                        <img src="{{ asset('images/diag.png') }}" alt="진단 대기" style="width: 120px; opacity: 0.4;">
                    </div>

                    {{-- 텍스트 안내 --}}
                    <p class="text-muted fs-5 mb-0">{{ config('app.name') }} 이 진단대기 상태에요</p>
                </div>

            @endif

        </div>    

    @endif

</div>