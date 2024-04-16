import { ref, computed } from 'vue';
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


    return {
        bidsData,
        bidsCountByUser,
        getBids,
    };
}
