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

    // 입찰 건수 
    const bidsCountByUser = computed(() => {
        const count = {};
        if (bidsData.value) {
            bidsData.value.forEach(bid => {
                const userId = bid.user_id; // 
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
        if (isLoading.value) return;
        isLoading.value = true;
        validationErrors.value = {};
        console.log("데이터:", auctionId, bidAmount, userId);
        const response = await axios.post("/api/bids", {
            user_id: userId,
            bid :{
                auction_id: auctionId,
                price: bidAmount
            }            
        }).then(async(response) => {
            console.log(response);
        }).catch((error) => {
            console.log("1");
            console.log(error);
            if (error.response?.data) {
                validationErrors.value = error.response.data.errors;
            }
        })
        .finally(() => (isLoading.value = false));
    };



    return {
        bidsData,
        bidsCountByUser,
        getBids,
        viewBids,
        bids,
        bid,
        submitBid,
        validationErrors,
        isLoading
    };
}