import { ref } from 'vue';
import axios from 'axios';

export default function useAuctions() {
    const auctionsData = ref([]);
    const pagination = ref({});

    const getAuctions = async (page = 1) => {
        try {
            const response = await axios.get(`/api/auctions?page=${page}`);
            auctionsData.value = response.data.data;
            pagination.value = response.data.meta;
        } catch (error) {
            console.error('Error fetching auctions:', error);
        }
    };

    return {
        auctionsData,
        pagination,
        getAuctions
    };
}
