import { ref } from 'vue';
import { cmmn } from '@/hooks/cmmn';
import { reactive } from 'vue';

export default function useLikes() {
    const likesData = ref([]);
    const pagination = ref({});
    const validationErrors = ref({});
    const { wicac } = cmmn();
    
    const like = reactive({
        likeable_type: `\Auction`,
        user_id:'',
        likeable_id:'',
    })
    
    const setLikes = async (like) => {
        const form = {
            like
        }
        return wicac.conn()
        //.log() // 로그 출력
        .url(`/api/likes`)
        .param(form)
        .callback(function(result) {       
            return result;
        })
        .post(); 
    }

    //내 관심 count가져오기
    const getMyLikesCount = async(userId = '') => {
            return wicac.conn()
            .url(`/api/auctions`)
            .param({mode:'count'})
            .where([`likes.user_id:${userId}`])
            .with(['likes'])
            .callback(function(result) {
                return result;
            }).get();
    }

    const getLikes = async (likeableType = 'Auction', userId = null) => {
        console.log(userId);
        const apiList = [`likes.likeable_type:like:${likeableType}`];

        if (userId) {
            apiList.push(`likes.user_id:${userId}`);
        }

        let page = 1;
        let allData = [];
        let hasMorePages = true;

        while (hasMorePages) {
            try {
                const result = await wicac.conn()
                    //.log() // 로그 출력
                    .url(`/api/likes`)
                    .where(apiList)
                    .page(`${page}`) // 페이지 0 또는 주석 처리시 기능 안함
                    .callback(function(result) {
                        return result;
                    })
                    .get();

                allData = allData.concat(result.data);
                pagination.value = result.rawData.data.meta;
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

    const deleteLike = (id => {
        return wicac.conn()
        .url(`/api/likes/${id}`)
        //.log()
        .callback(function(result){
            console.log(result);
            return result;
        })
        .delete();

    }) 
    return {
        like,
        setLikes,
        getLikes,
        deleteLike,
        likesData,
        pagination,
        validationErrors,
        isAuctionFavorited,
        getMyLikesCount,
    };
}
