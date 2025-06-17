export function setupLikeListeners() {
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

  function initState(auctionId, likeId, isLiked = false) {
    if (!window.likeStates) window.likeStates = {};
    if (!window.likeStates[auctionId]) {
      window.likeStates[auctionId] = {
        isLiked,
        likeId: likeId || null,
      };
    }
  }

  async function handleLike(store, auctionId, userId) {
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

  async function handleUnlike(store, likeId) {
    try {
      return await store.deleteLike(likeId);
    } catch (err) {
      console.error('handleUnlike error:', err);
      return null;
    }
  }

  function updateState(state, isLiked, likeId) {
    state.isLiked = isLiked;
    state.likeId = likeId;
  }

  function updateButtonVisual(auctionId, isLiked) {
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

  function showAlert(swal, title, text, icon) {
    swal.fire({ title, text, icon });
  }

  function forceReloadWithAlert(swal, title, text) {
    swal.fire({
      title,
      text,
      icon: 'warning',
    }).then(() => {
      location.reload();
    });
  }
}