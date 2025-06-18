{{-- 좋아요 기능 --}}
<div class="position-absolute top-0 end-0 z-3" x-show="window.userRole === 'dealer'">
    {{-- 이 위치에 좋아요 기능 컴포넌트 추가 --}}

    <div>
        <button
            class="btn fs-2"
            :id="`like-btn-${auction?.id}`"
            :data-auction-id="auction?.id"
            @click="window.dispatchEvent(new CustomEvent('toggle-like', {
                detail: {
                    auctionId: auction?.id,
                    userId: window.userId,
                    likeId: auction?.likes?.find(like => Number(like.user_id) === Number(window.userId) && String(like.likeable_id) === String(auction?.id))?.id || null
                }
            }))"
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            title="관심차량을 추가/삭제 합니다"
        >
            <i class="mdi mdi-heart border-1" :class="auction?.likes?.find(like => Number(like.user_id) === Number(window.userId) && String(like.likeable_id) === String(auction?.id))?.id ? 'text-danger' : 'text-white'"></i>
        </button>
    </div>
</div>
