import {ref,computed, inject} from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router'

// 입찰 데이터
export default function useBid() {
    const bidsData = ref([]);
    const bids = ref([]);
    const bid = ref({
        auction_id: '',
        price: ''
    });
    const validationErrors = ref({});
    const isLoading = ref(false);
    const swal = inject('$swal')

    const getBids = async () => {

        try {
            const response = await axios.get("/api/bids");
            bidsData.value = response.data.data;
        } catch (error) {
            console.error('Error fetching bids:', error);
        }
    };

    const getBidById = async (id) => {
        try {
            const response = await axios.get(`/api/bids/${id}`);
            return response.data.data;
        } catch (error) {
            console.error('Error fetching bids:', error);
        }
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
    
        try {
            const response = await axios.post("/api/bids", {
                user_id: userId,
                bid: {
                    auction_id: auctionId,
                    price: bidAmount
                }
            });
    
            console.log("응답 데이터:", response);
    
            if (response.status === 200 && response.data.status === "ok") {
                return {
                    success: true,
                    bidId: response.data.data.id,  // 입찰 ID 추출
                    message: response.data.message 
                };
            } else {
                return {
                    success: false,
                    message: response.data.message
                };
            }
        } catch (error) {
            console.error("입찰 제출 중 오류 발생:", error);
            let errorMessage = "입찰 제출 중 오류가 발생했습니다. 다시 시도해 주세요.";
            if (error.response?.data) {
                validationErrors.value = error.response.data.errors;  // 서버로부터 받은 에러를 저장
    
                // 특정 오류 메시지를 감지하여 구체적인 알림 표시
                if (error.response.data.message.includes('Numeric value out of range')) {
                    errorMessage = "입찰 금액이 너무 큽니다. 다시 시도해 주세요.";
                } else {
                    errorMessage = error.response.data.message;
                }
            }
            return {
                success: false,
                message: errorMessage
            };
        } finally {
            isLoading.value = false;  // 로딩 상태를 false로 설정
        }
    };
    
    

     const cancelBid = async (bidId) => {
        try {
            const response = await axios.delete(`/api/bids/${bidId}`);
            if (response.status === 204 || response.status === 200) {
                return { success: true };
            } else {
                return { success: false, };
            }
        } catch (error) {
            return { success: false, message: error.response?.data?.message};
        }
    }
    
    
      
    



    return {
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
        isLoading
    };
}