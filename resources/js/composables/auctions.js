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
        console.log(response);
        return response.data;
    } catch (error) {
        console.error('Error ID:', error);
        throw error; 
    }
};
const statusMap = {
    cancel: "취소",
    done: "경매완료",
    chosen: "선택완료",
    wait: "선택대기",
    ing: "경매진행",
    diag: "진단대기",
    ask: "신청완료"
};
const getStatusLabel = (status) => {
    return statusMap[status] || status;
};

// carinfo detail 정보를 가져오고 스토리지 저장 
const submitCarInfo = async () => {
    if (processing.value) return;  // 이미 처리중이면 다시 처리하지 않음
    processing.value = true;
    validationErrors.value = {};

    try {
        const response = await axios.post('/api/auctions/carInfo', carInfoForm);
        localStorage.setItem('carDetails', JSON.stringify(response.data.data));  // 데이터를 로컬 스토리지에 저장
        router.push({ name: 'sell' });  // 저장 후 sell 라우트로 이동
    } catch (error) {
        console.error(error);
        if (error.response?.data) {
            validationErrors.value = error.response.data.errors;  // 서버로부터 받은 에러 메시지 처리
        }
    } finally {
        processing.value = false;
    }
};

const updateAuction = async (auction) => {
    if (isLoading.value) return;

    isLoading.value = true;
    validationErrors.value = {};

    let serializedAuction = new FormData();
    for (let item in auction) {
        if (auction.hasOwnProperty(item)) {
            serializedAuction.append(item, auction[item]);
        }
    }

    axios.post('/api/auctions/' + auction.id, serializedAuction, {
        headers: {
            "content-type": "multipart/form-data"
        }
    })
        .then(response => {
            router.push({ name: 'auctions.index' });
            swal({
                icon: 'success',
                title: 'Auction updated successfully'
            });
        })
        .catch(error => {
            if (error.response?.data) {
                validationErrors.value = error.response.data.errors;
            }
        })
        .finally(() => isLoading.value = false);
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
        validationErrors,
        statusMap,
        getStatusLabel,
        updateAuction
    };
    
}
