import { ref } from 'vue';
import { cmmn } from '@/hooks/cmmn';

export default function useLikes() {
    const likesData = ref([]);
    const pagination = ref({});
    const validationErrors = ref({});
    const { callApi } = cmmn();

    const getLikes = async (likeableType = 'Auction', userId = null) => {
        const apiList = [`likes.likeable_type:like:${likeableType}`];

        if (userId) {
            apiList.push(`likes.user_id:${userId}`);
        }

        let page = 1;
        let allData = [];
        let hasMorePages = true;

        while (hasMorePages) {
            try {
                const response = await callApi({
                    _type: 'get',
                    _url: `/api/likes`,
                    _param: {
                        _where: apiList,
                        _page: `${page}`,
                        _paginate: 50
                    }
                });

                allData = allData.concat(response.data);
                pagination.value = response.rawData.data.meta;
                hasMorePages = page < pagination.value.last_page;
                page += 1;
            } catch (error) {
                console.error('Error fetching likes:', error);
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors;
                }
                throw error;
            }
        }

        likesData.value = allData;
        return allData;
    };

    const isAuctionFavorited = (auctionId) => {
        return likesData.value.some(like => like.likeable_id === auctionId);
    };

    return {
        getLikes,
        likesData,
        pagination,
        validationErrors,
        isAuctionFavorited,
    };
}
