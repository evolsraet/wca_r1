import { api } from '../../util/axios.js';

export default function (auctionData) {
  return {
    isLiked: false,
    likeId: null,

    async init() {
      const store = Alpine.store('likesStore');
      const userId = window.user.id;
      const auctionId = window.id;

      const data = Alpine.store('details')?.auctionData;
      console.log('✅ auctionData:', data);

      //console.log('auctionLikeButton', Alpine.store('shared'));

      // 1. 좋아요 리스트 불러오기 (해당 유저)
      // await store.getLikes('Auction', userId);

      // // 2. 현재 경매 ID가 좋아요 되었는지 확인
      // this.isLiked = store.isAuctionFavorited(auctionId);

      // // 3. 해당 좋아요 ID도 찾아서 저장 (삭제 시 필요)
      // const found = store.likesData.find(like =>
      //   like.likeable_id == auctionId &&
      //   String(like.user_id) == String(userId) &&
      //   like.likeable_type === 'App\\Models\\Auction'
      // );

      // this.likeId = found?.id ?? null;
    },

    toggleLike() {
      const store = Alpine.store('likesStore');

      if (!this.isLiked) {
        store.like = {
          likeable_type: 'App\\Models\\Auction',
          user_id: window.user.id,
          likeable_id: window.id,
        };

        store.setLikes().then(response => {
          if (response.status === 'ok') {
            this.isLiked = true;
            this.likeId = response.data?.id ?? null;

            Alpine.store('swal').fire({
              title: '추가',
              text: '관심차량을 추가했습니다.',
              icon: 'success',
              confirmButtonText: '확인',
            });
          }
        });

      } else {
        if (!this.likeId) {
          console.warn('삭제할 likeId가 없습니다.');
          return;
        }

        store.deleteLike(this.likeId).then(response => {
          if (response.status === 'ok') {
            this.isLiked = false;
            this.likeId = null;

            Alpine.store('swal').fire({
              title: '취소',
              text: '관심차량을 취소했습니다.',
              icon: 'info',
              confirmButtonText: '확인',
            });
          }
        });
      }
    }
  };
}