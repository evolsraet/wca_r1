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

    $final_at = $auction->final_at;
    
    // likes 테이블에서 user_id 와 likeable_id 가 일치하는 데이터 조회
    $like = \App\Models\Like::where('user_id', $user->id)->where('likeable_id', $id)->first();
    $isLiked = $like ? true : false;

    $auctionId = $id;
    $userId = $user?->id;
    $likeId = $like?->id;
    $isUserRole = auth()->user()->hasRole('user') ? 'user' : 'dealer';
@endphp

<script>
     let car_thumbnail = `{!! $auction->car_thumbnail !!}`;
    try {
        if (car_thumbnail.trim().startsWith('[')) {
            car_thumbnail = JSON.parse(car_thumbnail);
        } else {
            car_thumbnail = [car_thumbnail];
        }
    } catch (e) {
        car_thumbnail = [];
    }

    window.car_thumbnail = car_thumbnail;
    window.user = `{!! $user !!}`;
    window.id = `{!! $id ?? 0 !!}`;
    window.likeId = `{!! $like->id ?? null !!}`;
    window.isLiked = `{!! $isLiked ?? false !!}`;

</script>

<div x-data='auctionThumbnail()'>


@if($isUser && ($status == 'ask' || $status == 'diag'))
    <div class="text-center bg-gray-300 py-5 px-3 rounded position-relative" style="background-color: #f8f9fa;">
        {{-- 상태 뱃지 --}}
        <div class="badge bg-secondary position-absolute top-0 start-0 m-3">
            신청완료
        </div>

        {{-- 썸네일 이미지 (빈 썸네일 아이콘) --}}
        <div class="my-4">
            <img src="{{ asset('images/diag.png') }}" alt="진단 대기" style="width: 120px; opacity: 0.4;">
        </div>

        {{-- 텍스트 안내 --}}
        <p class="text-muted fs-5 mb-0">{{ config('app.name') }} 이 진단대기 상태에요</p>
    </div>
@endif

@if($isUser && ($status !== 'ask' && $status !== 'diag'))
    {{-- 경매 차량 이미지 --}}
    <div class="auction-thumbnail carousel-wrapper mb-3 position-relative" style="">

        {{-- 경매상태 뱃지 --}}
        <div class="position-absolute top-0 start-0 m-3 z-2" status-toggle-btn @click="hideStatusStep()">

            @if($status === 'ing')
            <div>
                {{-- 카운트다운 표시 --}}
                <div 
                class="bg-danger text-white rounded-3 px-2 py-1 fs-7 d-inline-flex align-items-center gap-1"
                x-init="window.dispatchEvent(new CustomEvent('start-countdown', { detail: { finalAt: '{{ $final_at }}' } }))"
                >
                    <i class="mdi mdi-clock-outline"></i>
                    <span data-timer>--:--:--</span>
                </div>

            </div>
            @else
            <div>
                {{-- 경매상태 뱃지 --}}
            <span class="auction-item-badge text-white p-2 rounded-3" 
                    id="auction-status-badge" 
                    data-bs-toggle="tooltip" 
                    data-bs-placement="top" 
                    title="클릭하면 경매상태바 를 숨깁니다." 
                    :class="$store.auctionStatus.get('{{ $status }}').class" 
                    x-text="$store.auctionStatus.get('{{ $status }}').label">
            </span>
            </div>
            @endif
        </div>


        {{-- 좋아요 기능 --}}
        @if($isUserRole === 'dealer')
        <div class="position-absolute top-0 end-0 z-2">
            {{-- 이 위치에 좋아요 기능 컴포넌트 추가 --}}
            {{-- <x-auctions.auctionLikeButton /> --}}
            <div>
                <div>
                    <button
                    class="btn fs-2 {{ $likeId ? 'text-danger' : 'text-white' }}"
                    id="like-btn-{{ $auction->id }}"
                    data-auction-id="{{ $auction->id }}"
                    @click="window.dispatchEvent(new CustomEvent('toggle-like', {
                        detail: {
                        auctionId: {{ $auction->id }},
                        userId: {{ $userId }},
                        likeId: {{ $likeId ?? 'null' }}
                        }
                    }))"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="관심차량을 추가/삭제 합니다"
                    >
                    <i class="mdi mdi-heart border-1 {{ $likeId ? 'text-danger' : 'text-white' }}"></i>
                    </button>
                </div>
            </div>
        </div>
        @endif

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
        x-show="car_thumbnail.length >= 1"
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
    </div>
@endif

</div>