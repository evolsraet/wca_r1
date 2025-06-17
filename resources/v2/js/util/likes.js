import { api } from './axios.js';

// 좋아요 기능
export const likesStore = {
  likesData: [],
  pagination: {},
  validationErrors: {},
  like: {
    likeable_type: 'App\\Models\\Auction',
    user_id: '',
    likeable_id: '',
  },

  async setLikes() {
    try {
      const response = await api.post('/api/likes', {
        like: this.like
      });
      return response.data;
    } catch (err) {
      console.error('setLikes error', err);
      this.validationErrors = err.response?.data?.errors ?? {};
      throw err;
    }
  },

  async getLikes(likeableType = 'Auction', userId = null) {
    const apiList = [`likes.likeable_type:like:${likeableType}`];
    if (userId) apiList.push(`likes.user_id:${userId}`);

    let page = 1;
    let allData = [];
    let hasMorePages = true;

    while (hasMorePages) {
      try {
        const response = await api.get('/api/likes', {
          params: {
            where: apiList,
            page: page,
          }
        });

        const result = response.data;

        if (!result || !Array.isArray(result.data)) {
        throw new Error('likes 응답 구조가 예상과 다릅니다.');
        }

        allData = allData.concat(result.data);
        this.pagination = result.meta ?? {};
        hasMorePages = page < this.pagination.last_page;
        page++;
      } catch (error) {
        console.error('getLikes error:', error);
        this.validationErrors = error.response?.data?.errors ?? {};
        throw error;
      }
    }

    this.likesData = allData;
    return allData;
  },

  isAuctionFavorited(auctionId) {
    return this.likesData.some(like => like.likeable_id === auctionId);
  },

  getLikeId(likeableType, userId, likeableId) {
    const found = this.likesData.find(like =>
      like.likeable_id == likeableId &&
      String(like.user_id) == String(userId) &&
      like.likeable_type === likeableType
    );
  
    return found?.id ?? null;
  },

  async deleteLike(id) {
    try {
      const response = await api.delete(`/api/likes/${id}`);
      return response.data;
    } catch (error) {
      console.error('deleteLike error', error);
      throw error;
    }
  },

  async getMyLikesCount(userId = '') {
    try {
      const response = await api.get('/api/auctions', {
        params: {
          mode: 'count',
          where: [`likes.user_id:${userId}`],
          with: ['likes']
        }
      });
      return response.data;
    } catch (error) {
      console.error('getMyLikesCount error', error);
      throw error;
    }
  }
};

// 좋아요 이벤트 리스너
export const likeListeners = () => {
  window.addEventListener('toggle-like', async (e) => {
    const { auctionId, userId, likeId } = e.detail;
    const isInitiallyLiked = !!likeId;

    initState(auctionId, likeId, isInitiallyLiked);

    const state = window.likeStates[auctionId];
    const store = Alpine.store('likesStore');
    const swal = Alpine.store('swal');

    if (!state.isLiked) {
      const response = await handleLike(store, auctionId, userId);
      if (response?.status === 'ok') {
        updateState(state, true, response.data?.id);
        updateButtonVisual(auctionId, true);
        showAlert(swal, '추가', '관심차량을 추가했습니다.', 'success');
      } else {
        console.warn('좋아요 실패 → 새로고침합니다');
        forceReloadWithAlert(swal, '좋아요 추가 실패', '새로고침 후 다시 시도해 주세요.');
      }
    } else {
      if (!state.likeId) {
        console.warn('취소 실패 - likeId 없음 → 새로고침합니다');
        forceReloadWithAlert(swal, '좋아요 정보 오류', '새로고침 후 다시 시도해 주세요.');
        return;
      }

      const response = await handleUnlike(store, state.likeId);
      if (response?.status === 'ok') {
        updateState(state, false, null);
        updateButtonVisual(auctionId, false);
        showAlert(swal, '취소', '관심차량을 취소했습니다.', 'info');
      } else {
        console.warn(`삭제 실패 → 강제 리로드`);
        forceReloadWithAlert(swal, '좋아요 취소 실패', '정보가 일치하지 않아 새로고침합니다.');
      }
    }
  });

  const initState = (auctionId, likeId, isLiked = false) => {
    if (!window.likeStates) window.likeStates = {};
    if (!window.likeStates[auctionId]) {
      window.likeStates[auctionId] = {
        isLiked,
        likeId: likeId || null,
      };
    }
  }

  const handleLike = async (store, auctionId, userId) => {
    store.like = {
      likeable_type: 'App\\Models\\Auction',
      user_id: userId,
      likeable_id: auctionId,
    };
    try {
      return await store.setLikes();
    } catch (err) {
      console.error('handleLike error:', err);
      return null;
    }
  }

  const handleUnlike = async (store, likeId) => {
    try {
      return await store.deleteLike(likeId);
    } catch (err) {
      console.error('handleUnlike error:', err);
      return null;
    }
  }

  const updateState = (state, isLiked, likeId) => {
    state.isLiked = isLiked;
    state.likeId = likeId;
  }

  const updateButtonVisual = (auctionId, isLiked) => {
    const btn = document.querySelector(`[data-auction-id="${auctionId}"]`);
    if (!btn) return;

    btn.classList.remove('text-danger', 'text-white');
    btn.classList.add(isLiked ? 'text-danger' : 'text-white');

    const icon = btn.querySelector('i');
    if (icon) {
      icon.classList.remove('text-danger', 'text-white');
      icon.classList.add(isLiked ? 'text-danger' : 'text-white');
    }
  }

  const showAlert = (swal, title, text, icon) => {
    swal.fire({ title, text, icon });
  }

  const forceReloadWithAlert = (swal, title, text) => {
    swal.fire({
      title,
      text,
      icon: 'warning',
    }).then(() => {
      location.reload();
    });
  }
}