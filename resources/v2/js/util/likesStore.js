import { api } from '../util/axios.js';

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