import { reactive, ref,inject } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import store from "../store";
import useUsers from "./users";

export default function useAuctions() {
    const showModal = ref(false);
    const auctionsData = ref([]);
    const auction = ref({});
    const pagination = ref({});
    const router = useRouter();;
    const processing = ref(false);
    const validationErrors = ref({});

    const isLoading = ref(false);
    const swal = inject('$swal');
    const { getUser } = useUsers();
    const hope_price = ref('');
    const carInfoForm = reactive({
        owner: "",
        no: "",
        forceRefresh: "" 
    });

// 경매 내용 통신 (페이지까지)
const getAuctions = async (page = 1) => {
    try {
        const response = await axios.get(`/api/auctions?page=${page}`);
        
        auctionsData.value = response.data.data;

        pagination.value = response.data.meta;

        console.log('Pagination:', pagination.value);

        for (const auction of auctionsData.value) {
            if (auction.win_bid) {
                const data = await getUser(auction.win_bid.user_id);
                const name = data.dealer.name;
                auction.dealer_name = name;
            } else {
                auction.value.dealer_name = null; 
            }
        };

        console.log('Auctions:', auctionsData.value);
    } catch (error) {
        console.error('Error fetching auctions:', error);
    }
};


    
// 경매 ID를 이용해 경매 상세 정보를 가져오는 함수
const getAuctionById = async (id) => {
    try {
        // API 경로에서 {auction} 부분을 실제 ID로 치환하여 요청
        const response = await axios.get(`/api/auctions/${id}`);
        auction.value = response.data.data;
        if (auction.value.win_bid) {
            const data = await getUser(auction.value.win_bid.user_id);
            const name = data.dealer.name;
            auction.value.dealer_name = name;
        } else {
            auction.value.dealer_name = null; 
        }
        return response.data;
    } catch (error) {
        console.error('Error ID:', error);
        throw error; 
    }
};
// 상태를 
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
        console.log("data",response.data);
    } catch (error) {
        console.error(error);
        if (error.response?.data) {
            validationErrors.value = error.response.data.errors;  // 서버로부터 받은 에러 메시지 처리
        }
    } finally {
        processing.value = false;
    }
};

const refreshCarInfo = async () => {
    if (processing.value) return;  // 이미 처리 중이면 다시 처리하지 않음
    processing.value = true;
    validationErrors.value = {};

    try {
        const carDetails = JSON.parse(localStorage.getItem('carDetails'));
        if (!carDetails || !carDetails.owner || !carDetails.no) {
            throw new Error("Owner and No fields are required in carDetails");
        }

        console.log("Refreshing with:", carDetails);

        const response = await axios.post('/api/auctions/carInfo', {
            owner: carDetails.owner,
            no: carDetails.no,
            forceRefresh: 'true'
        });

        localStorage.setItem('carDetails', JSON.stringify(response.data.data));
        console.log("Updated carDetails:", response.data.data);  


        const lastRefreshTimes = JSON.parse(localStorage.getItem('lastRefreshTimes')) || {};
        lastRefreshTimes[`${carDetails.owner}-${carDetails.no}`] = new Date().toISOString();
        localStorage.setItem('lastRefreshTimes', JSON.stringify(lastRefreshTimes));

    } catch (error) {
        console.error(error);
        if (error.response?.data) {
            validationErrors.value = error.response.data.errors;  
        }
        throw error;  
    } finally {
        processing.value = false;
    }
};


const AuctionCarInfo = async (carInfoForm) => {
    if (processing.value) return;  
    processing.value = true;
    validationErrors.value = {};
  
    try {
      const response = await axios.post('/api/auctions/carInfo', carInfoForm);
      return response.data; // response 데이터를 반환
    } catch (error) {
      console.error(error);
      if (error.response?.data) {
        validationErrors.value = error.response.data.errors;
      } else {
        throw new Error('Unknown error');
      }
    } finally {
      processing.value = false;
    }
  };
  

 const createAuction = async (auctionData) => {
    if (processing.value) return;
    processing.value = true;
    validationErrors.value = {};

    try {
        const response = await axios.post('/api/auctions', auctionData);
        return response.data; 
    } catch (error) {
        console.error(error);
        if (error.response?.data) {
            validationErrors.value = error.response.data.errors;
        }
        swal({
            icon: 'error',
            title: 'Failed to create auction'
        });
        throw error;
    } finally {
        processing.value = false;
    }
};
//재경매- (희망가) 변경
const AuctionReauction = async (id, data) => {
    if (isLoading.value) return;

    isLoading.value = true;
    validationErrors.value = {};

    const requestData = {
        auction: data
    };

    try {
        console.log(`Updating auction id: ${id} with data:`, data);
        const response = await axios.put(`/api/auctions/${id}`, requestData);

        console.log('response:', response.data);
        auction.value = response.data;
    } catch (error) {
        if (error.response?.data) {
            validationErrors.value = error.response.data.errors;
        }
        swal({
            icon: 'error',
            title: 'Failed to update auction status'
        });
    } finally {
        isLoading.value = false;
    }
};
//수정
const updateAuction = async (id,auction) => {
    const auctionForm = {
        auction
    }
    swal({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this action!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, edit it!',
        confirmButtonColor: '#ef4444',
        timer: 20000,
        timerProgressBar: true,
        reverseButtons: true
    })
        .then(result => {
            if (result.isConfirmed) {
                axios.put(`/api/auctions/${id}`,auctionForm)
                    .then(response => {
                        getAuctions()
                        router.push({name: 'auctions.index'})
                        swal({
                            icon: 'success',
                            title: 'Auction edit successfully'
                        })
                    })
                    .catch(error => {
                        swal({
                            icon: 'error',
                            title: 'Something went wrong'
                        })
                    })
            }
        })
   
}
//딜러 선택
const chosenDealer = async (id, data) => {
    if (isLoading.value) return;

    isLoading.value = true;
    validationErrors.value = {};

    const requestData = {
        auction: data
    };

    try {
        console.log(`Updating auction id: ${id} with data:`, data);
        const response = await axios.put(`/api/auctions/${id}`, requestData);
        console.log('response:', response.data);
        auction.value = response.data;
    } catch (error) {
        if (error.response?.data) {
            validationErrors.value = error.response.data.errors;
        }
        swal({
            icon: 'error',
            title: 'Failed to update auction status'
        });
    } finally {
        isLoading.value = false;
    }
};
    
// 상태 업데이트 
const updateAuctionStatus = async (id, status) => {
    if (isLoading.value) return;
    
    isLoading.value = true;
    validationErrors.value = {};

    const data = {
        auction: {
            status: status
        }
    };

    try {
        console.log(`status : ${status} auction id : ${id}`);
        const response = await axios.put(`/api/auctions/${id}`, data);
        console.log('response:', response.data);
        auction.value = response.data;
        swal({
            icon: 'success',
            title: 'Auction status updated successfully'
        });
    } catch (error) {
        if (error.response?.data) {
            validationErrors.value = error.response.data.errors;
        }
        swal({
            icon: 'error',
            title: 'Failed to update auction status'
        });
    } finally {``
        isLoading.value = false;
    }
};

 const updateAuctionPrice = async (auctionId, amount) => {
    if (isLoading.value) return;

    isLoading.value = true;
    validationErrors.value = {};

    const data = {
        auction: {
            amount: amount
        }
    };

    try {
        console.log(`Updating auction price: ${amount}`);
        const response = await axios.put(`/api/auctions/${auctionId}`, data);
        console.log('response:', response.data);
        swal({
            icon: 'success',
            title: 'Auction price updated successfully'
        });
        return response.data;
    } catch (error) {
        if (error.response?.data) {
            validationErrors.value = error.response.data.errors;
        }
        swal({
            icon: 'error',
            title: 'Failed to update auction price'
        });
        throw error;
    } finally {
        isLoading.value = false;
    }
};

const deleteAuction = async (id) => {
    swal({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this action!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        confirmButtonColor: '#ef4444',
        timer: 20000,
        timerProgressBar: true,
        reverseButtons: true
    })
        .then(result => {
            if (result.isConfirmed) {
                axios.delete(`/api/auctions/${id}`)
                    .then(response => {
                        getAuctions()
                        router.push({name: 'auctions.index'})
                        swal({
                            icon: 'success',
                            title: 'Auction deleted successfully'
                        })
                    })
                    .catch(error => {
                        swal({
                            icon: 'error',
                            title: 'Something went wrong'
                        })
                    })
            }
        })
        
      };

    return {
        AuctionCarInfo,
        chosenDealer,
        AuctionReauction,
        hope_price,
        deleteAuction,
        updateAuctionPrice,
        getAuctionById,
        useAuctions,
        getAuctions,
        auctionsData,
        pagination,
        carInfoForm,
        submitCarInfo,
        auction,
        processing,
        validationErrors,
        statusMap,
        getStatusLabel,
        updateAuctionStatus,
        createAuction,
        refreshCarInfo,
        updateAuction,
    };
    
}

