// useAuctionFilter.js
import { ref, computed, onMounted } from 'vue';
import useAuctions from "@/composables/auctions";

export function useAuctionFilter() {
    const { auctionsData, getAuctions } = useAuctions();
    const currentFilter = ref('all');

    const menufilter = computed(() => {
        return auctionsData.value.filter(auction => {
            if (currentFilter.value === 'interested') {
                return auction.isInterested;
            }
            return true;
        });
    });

    function changeFilter(filter) {
        currentFilter.value = filter;
    }

    onMounted(async () => {
        await getAuctions();
    });

    return {
        currentFilter,
        changeFilter,
        menufilter
    };
}
