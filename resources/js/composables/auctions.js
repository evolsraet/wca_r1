import { reactive, ref,inject } from 'vue';
import { useRouter } from 'vue-router';
import store from "../store";
import useUsers from "./users";
import { cmmn } from '@/hooks/cmmn';

export default function useAuctions() {
    const showModal = ref(false);
    const auctionsData = ref([]);
    const auction = ref({});
    const pagination = ref({});
    const router = useRouter();
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
    const { wicac , wica } = cmmn();


    const adminGetAuctions = async (
        page = 1,
        column = '',
        direction = '',
        status = 'all',
        search_title = ''
    ) => {
        const apiList = [];

        if(status != 'all'){
            apiList.push(`auctions.status:${status}`)
        }

        return wicac.conn()
        .url(`/api/auctions`)
        .log()
        .where(apiList)
        .order([
            [`${column}`,`${direction}`]
        ])
        .page(`${page}`)
        .search(search_title)
        .callback(function(result) {
            auctionsData.value = result.data;
            pagination.value = result.rawData.data.meta;
            return result.data;
        })
        .get();

    }

    const getAuctions = async (page = 1, isReviews = false , status = 'all') => {
        const apiList = [];
    
        if(status != 'all'){
            apiList.push(`auctions.status:${status}`)
        }

        if(isReviews){
    
            wicac.conn()
            //.log() //로그 출력
            .url(`/api/auctions`) //호출 URL
            .where([
                'auctions.status:done',
                'auctions.bid_id:>:0'
            ]) 
            .with([
                'reviews'
            ]) 
            .doesnthave([
                'reviews',
            ])
            .page(`${page}`) //페이지 0 또는 주석 처리시 기능 안함
            .callback(function(result) {
                auctionsData.value = result.data;
                pagination.value = result.rawData.data.meta;
            })
    
            .get();
            
        } else {
    
            return wicac.conn()
            .log() //로그 출력
            .url(`/api/auctions`) //호출 URL
            .with(['bids','likes'])
            .where(apiList) 
            .page(`${page}`) //페이지 0 또는 주석 처리시 기능 안함
            .callback(function(result) {
                auctionsData.value = result.data;
                pagination.value = result.rawData.data.meta;
            })
            //.log()
            .get();
    
        }
    };

//관리자페이지 - 입금관리 dlvr상태 리스트 가져오기 
const adminGetDepositAuctions = async(
    page = 1,
    column = '',
    direction = '',
    status = 'dlvr,done',
    search_title=''
) => {
    const apiList = [];
    apiList.push(`auctions.status:whereIn:${status}`)

    return wicac.conn()
    .url(`/api/auctions`)
    .where(apiList)
    .order([
        [`${column}`,`${direction}`]
    ])
    .page(`${page}`)
    .search(search_title)
    .callback(function(result) {
        auctionsData.value = result.data;
        pagination.value = result.rawData.data.meta;
        
        return result.data;
    })
    .get();
}

//관리자페이지 - auction 상태 별 개수 가져오기
const getStatusAuctionsCnt = async(
    status = 'all',
) => {
    const apiList = [];
    if(status != 'all'){
        apiList.push(`auctions.status:${status}`)
    }
    
    return wicac.conn()
    .url(`/api/auctions`)
    .where(apiList)
    .callback(function(result) {
        return result.page.total;
    })
    .get();
}
    
// 경매 ID를 이용해 경매 상세 정보를 가져오는 함수
const getAuctionById = async (id) => {
    
    return wicac.conn()
    .log() //로그 출력
    .url(`/api/auctions/${id}`) //호출 URL
    .with(['bids','reviews','likes'])
    //.page(`${page}`) //페이지 0 또는 주석 처리시 기능 안함
    .callback(async function(result) {
        console.log(result);
        auction.value = result.data;
        auction.value.dealer_name = null;
        if (auction.value.win_bid) {
            console.log(auction.value.win_bid.user_id);
            const data = await getUser(auction.value.win_bid.user_id);

            const name = data.dealer.name;
            auction.value.dealer_name = name;
        } else {
            auction.value.dealer_name = null; 
        } 
        return result;
    })
    .get();

};
// 상태를 
const statusMap = {
    cancel: "취소",
    done: "경매완료",
    chosen: "선택완료",
    wait: "선택대기",
    ing: "경매진행",
    diag: "진단대기",
    dlvr: "탁송진행",
    ask: "신청완료"
};
const getStatusLabel = (status) => {
    return statusMap[status] || status;
};

// carinfo detail 정보를 가져오고 스토리지 저장 
const submitCarInfo = async () => {
    if (processing.value) return;  // 이미 처리중이면 다시 처리하지 않음

    processing.value = false;
    validationErrors.value = {};

    return wicac.conn()
    //.log() //로그 출력
    .url('/api/auctions/carInfo') //호출 URL
    .param(carInfoForm)
    .callback(function(result) {
        if(result.isError){
            validationErrors.value = result.rawData.response.data.errors;         
            return result;
        }else{
            localStorage.setItem('carDetails', JSON.stringify(result.data));  // 데이터를 로컬 스토리지에 저장
            return result;
        }
    })
    .post();

    
};

const refreshCarInfo = async () => {
    if (processing.value) return;  // 이미 처리 중이면 다시 처리하지 않음
    processing.value = true;
    validationErrors.value = {};

    const carDetails = JSON.parse(localStorage.getItem('carDetails'));
    if (!carDetails || !carDetails.owner || !carDetails.no) {
        throw new Error("Owner and No fields are required in carDetails");
    }

    wicac.conn()
    .url(`/api/auctions/carInfo`)
    .param({
        owner: carDetails.owner,
        no: carDetails.no,
        forceRefresh: 'true'
    })
    .callback(function (result) {
        if(result.isError){
            validationErrors.value = result.rawData.response.data.errors;
            throw new Error;          
        } else {
            console.log(result);
            localStorage.setItem('carDetails', JSON.stringify(result.data));
            console.log("Updated carDetails:", result.data);  
            const lastRefreshTimes = JSON.parse(localStorage.getItem('lastRefreshTimes')) || {};
            lastRefreshTimes[`${carDetails.owner}-${carDetails.no}`] = new Date().toISOString();
            localStorage.setItem('lastRefreshTimes', JSON.stringify(lastRefreshTimes));
        }
        processing.value = false;
    })
    .post();

};


const AuctionCarInfo = async (carInfoForm) => {
    if (processing.value) return;  
    processing.value = true;
    validationErrors.value = {};
  
    return await wicac.conn()
    .url(`/api/auctions/carInfo`)
    .param(carInfoForm)
    .callback(function (result) {
        if(result.isError){
            processing.value = false;
            validationErrors.value = result.rawData.response.data.errors;
            throw new Error;          
        } else {
            processing.value = false;
            return result;
        }
    })
    .post();

  };
  

 const createAuction = async (auctionData) => {
    if (processing.value) return;
    processing.value = true;
    validationErrors.value = {};

    let payload = {
        auction : {
            owner_name: auctionData.auction.owner_name,
            car_no: auctionData.auction.car_no,
            final_at: auctionData.auction.final_at,
            region: auctionData.auction.region,
            addr1: auctionData.auction.addr1,
            addr2: auctionData.auction.addr2,
            bank: auctionData.auction.bank,
            account: auctionData.auction.account,
            memo: auctionData.auction.memo,
            addr_post: auctionData.auction.addr_post,
            status: auctionData.auction.status
        }
    }

    /*
    const formData = new FormData();
    formData.append('auction', JSON.stringify(payload.auction));
    if(auction.file_user_owner){
        formData.append('file_user_owner', auction.file_user_owner);
    }
    //const fileResult = await fileUserOwnerUpdate(userData.file_user_owner,userData.id);

    
    
    return wicac.conn()
    .url(`/api/auctions`)
    .param(formData) 
    .multipart()
    .callback(function (result) {
        if(result.isError){
            validationErrors.value = result.rawData.response.data.errors;
            //fileUserOwnerDeleteById(userData.id);
            throw new Error;          
        } else {
            processing.value = false;
            return result.isSuccess;
        }
    })
    .post();
    */
    return wicac.conn()
    .url(`/api/auctions`)
    .param(payload)
    .callback(function (result) {
        if(result.isError){
            validationErrors.value = result.rawData.response.data.errors;
            //fileUserOwnerDeleteById(userData.id);
            throw new Error;          
        } else {
            wica.ntcn(swal)
            .title('')
            .useHtmlText()
            .icon('I')
            .callback(function(result) {
                //console.log(result);
            }).alert('경매 신청이 완료되었습니다.');
            processing.value = false;
            return result.isSuccess;
        }
    })
    .post();

};
//재경매- (희망가) 변경
const AuctionReauction = async (id, data) => {
    if (isLoading.value) return;

    isLoading.value = true;
    validationErrors.value = {};

    const requestData = {
        auction: data
    };

    wicac.conn()
    .url(`/api/auctions/${id}`) 
    .param(requestData)
    .callback(function(result) {
        console.log('wicac.conn callback ' , result);
        if(result.isError){
            validationErrors.value = result.rawData.response.data.errors;
            wica.ntcn(swal)
            .title('매물 상태 업데이트 중 오류가 발생하였습니다.')
            .useHtmlText()
            .icon('E')
            .callback(function(result) {
                //console.log(result);
            }).alert('관리자에게 문의해주세요.');
        } else {
            auction.value = result.data;
        }
        isLoading.value = false;
    })
    .put();
    
};
//수정
const updateAuction = async (id,auction) => {
    console.log('111111');
    const auctionForm = {
        auction : {
            bank : auction.bank,
            account : auction.account,
            car_no: auction.car_no,
            owner_name: auction.owner_name,
            bank: auction.bank,
            account: auction.account,
            memo: auction.memo,
            region:auction.region,
            addr_post: auction.addr_post,
            status : auction.status,
            addr1: auction.addr1,
            addr2: auction.addr2,
            final_at: auction.final_at,
            choice_at: auction.choice_at,
            done_at: auction.done_at,
            success_fee: auction.success_fee,
            diag_fee: auction.diag_fee,
            total_fee: auction.total_fee,
            hope_price: auction.hope_price,
            final_price: auction.final_price,
        }
    }
    console.log(JSON.stringify(auctionForm));

    const formData = new FormData();
    formData.append('auction', JSON.stringify(auctionForm.auction));
    if(auction.file_user_owner){
        formData.append('file_user_owner', auction.file_user_owner);
    }

    wica.ntcn(swal)
    .title('변경하시겠습니까?') // 알림 제목
    .icon('Q') //E:error , W:warning , I:info , Q:question
    .useHtmlText()
    .callback(async function(result) {
        if(result.isOk){
            wicac.conn()
            .url(`/api/auctions/${id}`) //호출 URL
            //.multipart() //첨부파일 있을 경우 선언
            .param(auctionForm)
            .callback(function(result) {
                console.log('wicac.conn callback ' , result);
                if(result.isSuccess){
                    wica.ntcn(swal)
                    .useHtmlText()
                    .icon('I')
                    .callback(function(result) {
                        if(result.isOk){
                            //location.reload();
                            getAuctions()
                            router.push({name: 'auctions.index'})
                        }
                    }).alert('변경되었습니다');
                }else{
                    wica.ntcn(swal)
                    .title('오류가 발생하였습니다.')
                    .useHtmlText()
                    .icon('E')
                    .callback(function(result) {
                        console.log(result);
                    }).alert('관리자에게 문의해주세요.');
                }
            })
            .put();
        }
    }).confirm();
}

//딜러 선택
const chosenDealer = async (id, data) => {
    console.log('=====딜러선택------==================');
    if (isLoading.value) return;

    isLoading.value = true;
    validationErrors.value = {};

    const requestData = {
        auction: data
    };

    wicac.conn()
        .url(`/api/auctions/${id}`) 
        .param(requestData)
        .callback(function(result) {
            console.log('wicac.conn callback ' , result);
            if(result.isError){
                validationErrors.value = result.rawData.response.data.errors;
                wica.ntcn(swal)
                .title('매물 상태 업데이트 중 오류가 발생하였습니다.')
                .useHtmlText()
                .icon('E')
                .callback(function(result) {
                    //console.log(result);
                }).alert('관리자에게 문의해주세요.');
            } else {
                auction.value = result.data;
            }
            isLoading.value = false;
        })
        .put();

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
    return wicac.conn()
    .url(`/api/auctions/${id}`)
    .param(data) 
    .callback(function(result) {
        /*
        if(result.isSuccess){
            auction.value = result.data;
        }else{
            console.log(result.msg);
        }
        */
        isLoading.value = false;
        return result;
    })
    .put();
    
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

    return await wicac.conn()
    .url(`/api/auctions/${auctionId}`)
    .param(data)
    .callback(function (result) {
        if(result.isError){
            processing.value = false;
            validationErrors.value = result.rawData.response.data.errors;
            wica.ntcn(swal)
                .title('매물 가격 업데이트 중 오류가 발생하였습니다.')
                .useHtmlText()
                .icon('E')
                .callback(function(result) {
                    //console.log(result);
            }).alert('관리자에게 문의해주세요.');
            throw new Error;          
        } else {
            processing.value = false;
            return result;
        }
    })
    .put();

};

const deleteAuction = async (id,urlPath) => {

    wica.ntcn(swal)
    .title('삭제하시겠습니까?') // 알림 제목
    .icon('W') //E:error , W:warning , I:info , Q:question
    .callback(function(result) {
        if(result.isOk){
            wicac.conn()
            .url(`/api/auctions/${id}`) //호출 URL
            .callback(function(result) {
                if(result.isSuccess){
                    wica.ntcn(swal)
                    .icon('I') //E:error , W:warning , I:info , Q:question
                    .callback(function(result) {
                        if(result.isOk){
                            if(urlPath == 'auction'){
                                getAuctions();
                                router.push({name: 'auctions.index'})
                            } else if(urlPath == 'deposit'){
                                adminGetDepositAuctions();
                                router.push({name: 'deposit.index'})
                            } else{
                                router.push({name: 'admin.index'})
                            }              
                        }
                    })
                    .alert('삭제되었습니다.');
                }else{
                    wica.ntcn(swal)
                    .title('오류가 발생하였습니다.')
                    .useHtmlText()
                    .icon('I') //E:error , W:warning , I:info , Q:question
                    .alert('관리자에게 문의해주세요.');
                }
            })
            .delete();
        }
    })
    .confirm('삭제된 정보는 복구할 수 없습니다.');

};


    return {
        adminGetAuctions,
        adminGetDepositAuctions,
        getStatusAuctionsCnt,
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
        updateAuction
    };
    
}

