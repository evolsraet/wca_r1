import { ref, computed, inject } from 'vue';
import { cmmn } from '@/hooks/cmmn';

// 입찰 데이터
export default function useBid() {
    const { wicac , wica } = cmmn();
    const bidsData = ref([]);
    const bids = ref([]);
    const bid = ref({
        auction_id: '',
        price: ''
    });
    const validationErrors = ref({});
    const isLoading = ref(false);
    const swal = inject('$swal');
    const auctionsData = ref([]);
    const bidPagination = ref({});
    
    /**
    const getBids = async () => {
        let allBids = [];
        let currentPage = 1;
        let lastPage = 1;

        try {
            let response = await axios.get("/api/bids", {
                params: {
                    page: currentPage
                }
            });
            allBids = response.data.data;
            lastPage = response.data.meta.last_page;
            currentPage++;

            while (currentPage <= lastPage) {
                response = await axios.get("/api/bids", {
                    params: {
                        page: currentPage
                    }
                });
                allBids = allBids.concat(response.data.data);
                currentPage++;
            }

            bidsData.value = allBids;
            pagination.value = {
                currentPage: currentPage - 1,
                lastPage: lastPage,
                total: response.data.meta.total
            };
        } catch (error) {
            console.error('Error fetching bids:', error);
        }
    }; */
    
    //isSelect => 선택 차량 (선택,탁송 filter)
    //isMyBid => 진행 중 입찰 건 filter
    const getBids = async (page = 1, isSelect = false, isMyBid = false , status = "all") => {
        let request = wicac.conn()
        .log()
        .url('/api/bids')
        .with(['auction'])
        .page(`${page}`)
        if (isSelect) {
            const statusFilter = status === 'all' ? 'dlvr,chosen' : status;
            request = request.whereOr('auction.status',`${statusFilter}`)           
        }
    
        if (isMyBid) {
            request = request.whereOr('auction.status','ing,wait')
        }
        return request.callback(function(result) {SS
            console.log("~~~~",result);
            bidsData.value = result.data;
            bidPagination.value = result.rawData.data.meta;
            return result.data;
        }).get();
        
    };

    //페이징 안 한 전체 bid
    const getHomeBids = async (mainIsOk = false) => {
        let request = wicac.conn()
        //.log()
        .url(`/api/bids`)
        .with(['auction'])
        .pageLimit(10000)
        if(mainIsOk){
            request = request.whereOr('auction.status','ing,wait')
        }
        return request.callback(function(result) {
            //console.log(result);
            bidsData.value = result.data;
            //bidPagination.value = result.rawData.data.meta;
            return result.data;
        }).get();

    } 
    const getBidById = async (id) => {
        return wicac.conn()
        .url(`/api/bids/${id}`) 
        .callback(function(result) {
            if(result.isSuccess){
                return result.data;
            }else{
                wica.ntcn(swal)
                .title('오류가 발생하였습니다.')
                .icon('E') //E:error , W:warning , I:info , Q:question
                .alert('관리자에게 문의해주세요.');
            }
        })
        .get();
    };

    // 입찰 건수 
    const bidsCountByUser = computed(() => {
        const count = {};
        if (bidsData.value) {
            bidsData.value.forEach(bid => {
                const userId = bid.user_id;
                if (count[userId]) {
                    count[userId]++;
                } else {
                    count[userId] = 1;
                }
            });
        }
        return count;
    });

    // 딜러-메인 뷰 ::선택 완료 차량 (view -> 최대 2개설정)
    const viewBids = computed(() => {
        return bidsData.value.slice(0, 2);
    });

    const submitBid = async (auctionId, bidAmount, userId) => {
        if (isLoading.value) return;  // 이미 로딩 중이면 함수 실행을 중지
        isLoading.value = true;
        validationErrors.value = {};

        return wicac.conn()
        .url(`/api/bids`) //호출 URL
        .param({
            user_id: userId,
            bid: {
                auction_id: auctionId,
                price: bidAmount
            }
        })
        .callback(function(result) {
            if(result.isSuccess){
                isLoading.value = false;
                return {
                    success: true,
                    bidId: result.data.id,  // 입찰 ID 추출
                    message: result.data.message
                };
            }else{
                isLoading.value = false;
                let errorMessage = "입찰 제출 중 오류가 발생했습니다. 다시 시도해 주세요.";
                if (result.rawData.response.data.message.includes('Numeric value out of range')) {
                    errorMessage = "입찰 금액이 너무 큽니다. 다시 시도해 주세요.";
                } else {
                    errorMessage = result.rawData.response.data.message;
                }
                return {
                    success: false,
                    message: errorMessage
                };
            }
         })
        .post();
    };

    const cancelBid = async (bidId) => {
        return wicac.conn()
        .url(`/api/bids/${bidId}`)
        .callback(function(result) {
            if(result.isSuccess){
                return { success: true };
            }else{
                return { success: false };
            }
        })
        .delete();
    };

    const getBidsByUserId = async(userId) =>{
        const apiList = [];
        apiList.push(`bids.user_id:${userId}`);
        
        return wicac.conn()
        .url(`/api/bids`)
        .where(apiList)
        .pageLimit(10000)
        .callback(function(result) {
            if(result.isSuccess){
                return result.data; 
            }else{
                wica.ntcn(swal)
                .title('오류가 발생하였습니다.')
                .useHtmlText()
                .icon('I') //E:error , W:warning , I:info , Q:question
                .alert('관리자에게 문의해주세요.');
            }
        })
        .get();
    };

    return {
        getHomeBids,
        cancelBid,
        bidsData,
        bidsCountByUser,
        getBids,
        getBidById,
        viewBids,
        bids,
        bid,
        submitBid,
        validationErrors,
        isLoading,
        auctionsData,
        bidPagination,
        getBidsByUserId,
    };
}
