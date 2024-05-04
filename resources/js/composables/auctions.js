import { reactive, ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import store from "../store";

export default function useAuctions() {
    const auctionsData = ref([]);
    const pagination = ref({});
    const router = useRouter();;
    const processing = ref(false);
    const validationErrors = ref({});
    const carInfoForm = reactive({
        owner: "",
        no: "",
        forceRefresh: "" 
    });

    const getAuctions = async (page = 1) => {
        try {
            const response = await axios.get(`/api/auctions?page=${page}`);
            auctionsData.value = response.data.data;
            pagination.value = response.data.meta;
        } catch (error) {
            console.error('Error fetching auctions:', error);
        }
    };
    

// 경매 ID를 이용해 경매 상세 정보를 가져오는 함수
const getAuctionById = async (id) => {
    try {
        // API 경로에서 {auction} 부분을 실제 ID로 치환하여 요청
        const response = await axios.get(`/api/auctions/${id}`);
        return response.data;
    } catch (error) {
        console.error('Error fetching auction by ID:', error);
        throw error; // 에러를 다시 던져서 호출 측에서 처리할 수 있게 함
    }
};


    const submitCarInfo = async () => {
        if (processing.value) return;
        processing.value = true;
        validationErrors.value = {};
        
        try {
            const response = await axios.post("/api/auctions/carInfo", carInfoForm);
            console.log("success:", response);
            sessionStorage.setItem('carDetails', JSON.stringify(response.data.data));// 조회이기에 세션으로 저장
            console.log('data:', response.data.data);
            router.push({ name: "sell" });
        } catch (error) {
            console.error(error);
            if (error.response?.data) {
                console.error(error.response.data.message);
                console.error(error.response.data.errors);
                validationErrors.value = error.response.data.errors;
            }
        } finally {
            processing.value = false;
        }
    };
    

    
    return {
        getAuctionById,
        useAuctions,
        getAuctions,
        auctionsData,
        pagination,
        carInfoForm,
        submitCarInfo,
        processing,
        validationErrors
    };
    
}
