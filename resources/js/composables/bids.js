import {ref,computed} from 'vue';
import axios from 'axios';

// 입찰 데이터
export default function useBid() {
    const bidsData = ref([]);

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

    return {
        bidsData,
        bidsCountByUser,
        getBids,
        viewBids
    };
}