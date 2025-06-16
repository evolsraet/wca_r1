<div x-data="auctionLikeButton(auction)">
    <button class="btn text-white fs-2" 
            id="like-btn" 
            @click="toggleLike"
            data-bs-toggle="tooltip" 
            data-bs-placement="top" 
            :class="isLiked ? 'text-danger' : 'text-white'" 
            title="좋아요" 
    >
        <i class="mdi mdi-heart border-1" :class="{'text-danger': isLiked}"></i>
    </button>
</div>