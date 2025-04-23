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
    const bidsDataAuction = ref([]);
    const bidPaginationAuction = ref({});

    const isLoading = ref(false);
    const swal = inject('$swal');
    const { getUser } = useUsers();
    const hope_price = ref('');
    const carInfoForm = reactive({
        owner: "",
        no: "",
        forceRefresh: "" 
    });
    const { wicac , wica, loadingSpinner } = cmmn();


    const adminGetAuctions = async (
        page = 1,
        column = '',
        direction = '',
        status = 'all',
        auctionType = 'all',
        search_title = ''
    ) => {
        const apiList = [];

        if(status != 'all'){
            apiList.push(`auctions.status:${status}`)
        }

        if(auctionType != 'all'){
            apiList.push(`auctions.auction_type:${auctionType}`)
        }

        return wicac.conn()
        .url(`/api/auctions`)
        //.log()
        .where(apiList)
        .order([
            [`${column}`,`${direction}`]
        ])
        .page(`${page}`)
        .pageLimit(12)
        .search(search_title)
        .callback(function(result) {
            auctionsData.value = result.data;
            pagination.value = result.rawData.data.meta;
            return result.data;
        })
        .get();

    }

    const getAuctionsByDealer = async (page = 1 , status="all",search_title='') => {
        /*
        if(status != 'all'){
            apiList.push(`auctions.status:${status}`)
        }*/

        let request = wicac.conn()
            .log()
            .url(`/api/auctions`)
            .search(search_title)
            .with(['bids', 'likes'])
            .pageLimit(12)
            .whereOr('auctions.status','ing,wait')
        if(page == "all"){
            request = request.pageLimit(10000);
        } else{
            request = request.page(`${page}`)
        }
    
        return request.callback(function(result) {
            auctionsData.value = result.data;
            pagination.value = result.rawData.data.meta;
            return result;
        }).get();
    }

    const getAuctionsByDealerLike = async (page = 1 , userId = null , status = "all" ,search_title = '') => {
        let request = wicac.conn()
            .url(`/api/auctions`)
            .search(search_title)
            .with(['likes'])
            .page(`${page}`)
            .pageLimit(12)
        if(userId != null){
            request = request.whereOr('likes.user_id',`${userId}`);
        } 
        if(status != 'all'){
            request = request.whereOr('auctions.status',`${status}`)
        }
        return request.callback(function(result) {
            return result;
        }).get();
    }

    const getAuctions = async (page = 1, isReviews = false , status = 'all', search_text='') => {
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
            .search(search_text)
            .with([
                'reviews'
            ]) 
            .doesnthave([
                'reviews',
            ])
            .page(`${page}`) //페이지 0 또는 주석 처리시 기능 안함
            .pageLimit(12)
            .callback(function(result) {
                auctionsData.value = result.data;
                pagination.value = result.rawData.data.meta;
            })
    
            .get();
            
        } else {
    
            return wicac.conn()
            //.log() //로그 출력
            .url(`/api/auctions`) //호출 URL
            .with(['bids','likes'])
            .search(search_text)
            .where(apiList) 
            .pageLimit(12)
            .page(`${page}`) //페이지 0 또는 주석 처리시 기능 안함
            .callback(function(result) {

                console.log('result',result);

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

    return wicac.conn()
    .url(`/api/auctions`)
    .whereOr('auctions.status',`${status}`)
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
    //.log() //로그 출력
    .url(`/api/auctions/${id}`) //호출 URL
    .with(['bids','reviews','likes'])
    //.page(`${page}`) //페이지 0 또는 주석 처리시 기능 안함
    .pageLimit(12)
    .callback(async function(result) {
        auction.value = result.data;
        auction.value.dealer_name = null;
        if (auction.value.win_bid) {
            const data = await getUser(auction.value.win_bid.user_id);
            const auctionUserData = await getUser(auction.value.user_id);

            const name = data.dealer.name;
            auction.value.dealer_name = name;
            auction.value.dealer = data.dealer;
            auction.value.user = auctionUserData;
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
    ask: "신청완료",
    totalDeposit : "입금완료",
    totalAfterFee : "수수료 입금완료"
};
const getStatusLabel = (status) => {
    return statusMap[status] || status;
};

// carinfo detail 정보를 가져오고 스토리지 저장 
const submitCarInfo = async () => {
    if (processing.value) return;  // 이미 처리중이면 다시 처리하지 않음
    loadingSpinner(true);
    processing.value = false;
    validationErrors.value = {};

    carInfoForm.mode = 'carInfo';

    return wicac.conn()
    //.log() //로그 출력
    .url('/api/auctions/carInfo') //호출 URL
    .param(carInfoForm)
    .callback(function(result) {
        if(result.isError){
            loadingSpinner(false);
            validationErrors.value = result.rawData.response.data.errors;         
            return result;
        }else{
            loadingSpinner(false);
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
    loadingSpinner(true);
    validationErrors.value = {};

    let payload = {
        auction : {
            auction_type: auctionData.auction.auction_type,
            owner_name: auctionData.auction.owner_name,
            car_no: auctionData.auction.car_no,
            region: auctionData.auction.region,
            addr1: auctionData.auction.addr1,
            addr2: auctionData.auction.addr2,
            bank: auctionData.auction.bank,
            account: auctionData.auction.account,
            memo: auctionData.auction.memo,
            addr_post: auctionData.auction.addr_post,
            status: auctionData.auction.status,
            is_biz:auctionData.auction.is_biz,
            diag_first_at: auctionData.auction.diag_first_at,
            diag_second_at: auctionData.auction.diag_second_at,
            car_maker: auctionData.auction.car_maker,
            car_model: auctionData.auction.car_model,
            car_model_sub: auctionData.auction.car_model_sub,
            car_grade: auctionData.auction.car_grade,
            car_grade_sub: auctionData.auction.car_grade_sub,
            car_year: auctionData.auction.car_year,
            car_first_reg_date: auctionData.auction.car_first_reg_date,
            car_mission: auctionData.auction.car_mission,
            car_fuel: auctionData.auction.car_fuel,
            car_price_now: auctionData.auction.car_price_now,
            car_price_now_whole: auctionData.auction.car_price_now_whole,
            car_thumbnail: auctionData.auction.car_thumbnail,
            car_km: auctionData.auction.car_km,
            car_status: auctionData.auction.car_status,
            car_condition: auctionData.auction.car_condition,
            is_business_owner: auctionData.auction.is_business_owner,
            is_agree: auctionData.auction.is_agree
        }
    }

    
    const formData = new FormData();
    formData.append('auction', JSON.stringify(payload.auction));
    if(auctionData.auction.file_auction_proxy){
        formData.append('file_auction_proxy', auctionData.auction.file_auction_proxy);
    }

    if(auctionData.auction.file_auction_car_license){
        formData.append('file_auction_car_license', auctionData.auction.file_auction_car_license);
    }

    if(auctionData.auction.file_auction_company_license){
        formData.append('file_auction_company_license', auctionData.auction.file_auction_company_license);
    }
    
    return wicac.conn()
    .url(`/api/auctions`)
    .param(formData) 
    .multipart()
    .callback(function (result) {
        if(result.isError){
            validationErrors.value = result.rawData.response.data.errors;
            //fileUserOwnerDeleteById(userData.id);
            processing.value = false;
            loadingSpinner(false);
            throw new Error;          
        } else {
            processing.value = false;
            loadingSpinner(false);
            const resultData = {
                unique_number: result.data.unique_number,
                isSuccess: result.isSuccess
            };
            return resultData;
        }
    })
    .post();
    

    /*
    return wicac.conn()
    .url(`/api/auctions`)
    .param(payload)
    .callback(function (result) {
        if(result.isError){
            validationErrors.value = result.rawData.response.data.errors;
            //fileUserOwnerDeleteById(userData.id);
            processing.value = false;
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
    */

};
//재경매- (희망가) 변경
const AuctionReauction = async (id, data) => {
    if (isLoading.value) return;

    isLoading.value = true;
    validationErrors.value = {};

    const requestData = {
        auction: data
    };

    if(data.status == 'wait'){
        requestData.mode = 'reauction';
    }

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
            success_fee: auction.success_fee,
            diag_fee: auction.diag_fee,
            diag_first_at: auction.diag_first_at,
            diag_second_at: auction.diag_second_at,
            total_fee: auction.total_fee,
            hope_price: auction.hope_price,
            final_price: auction.final_price,
            is_biz: auction.isBizChecked,
            dest_addr_post: auction.dest_addr_post,
            dest_addr1: auction.dest_addr1,
            dest_addr2: auction.dest_addr2,
        }
    }

    if(auction.choice_at){
        auctionForm.auction.choice_at = auction.choice_at;
    }

    if(auction.done_at){
        auctionForm.auction.done_at = auction.done_at;
    }

    if(auction.taksong_wish_at){
        auctionForm.auction.taksong_wish_at = auction.taksong_wish_at;
    }

    const formData = new FormData();

    if(auction.status == 'diag'){
        auctionForm.auction.status = 'ask';

        auctionForm.mode = 'diag';
        formData.append('mode', auctionForm.mode);
    }
    

    formData.append('auction', JSON.stringify(auctionForm.auction));
    if(auction.file_auction_proxy){
        formData.append('file_auction_proxy', auction.file_auction_proxy);
    }
    if(auction.file_auction_owner){
        formData.append('file_auction_owner', auction.file_auction_owner);
    }
    if(auction.file_auction_car_license){
        formData.append('file_auction_car_license', auction.file_auction_car_license);
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
            .param(formData)
            .multipartUpdate()
            .callback(function(result) {
                if(result.isSuccess){
                    wica.ntcn(swal)
                    .useHtmlText()
                    .icon('I')
                    .callback(function(result) {
                        if(result.isOk){
                            //location.reload();
                            getAuctions();
                            if(auction.deletFileList){
                                setDeleteFileAuction(auction.deletFileList);
                            }
                            router.push({name: 'auctions.index'})
                        }
                    }).alert('변경되었습니다');
                }else{
                    let errMsg = '관리자에게 문의해주세요.';
                    if(result.msg){
                        errMsg = result.msg;
                    }
                    wica.ntcn(swal)
                    .title('오류가 발생하였습니다.')
                    .useHtmlText()
                    .icon('E')
                    .callback(function(result) {
                        console.log(result);
                    }).alert(errMsg);
                }
            })
            .post();
        }
    }).confirm();
    
    
    /*
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
    */
}

//딜러 선택
const chosenDealer = async (id, data) => {
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
    
    loadingSpinner(true);
    isLoading.value = true;
    validationErrors.value = {};

    let isAccident = 0;
    if(status == 'ing'){
        isAccident = 1;
    }

    const data = {
        auction: {
            status: status,
            is_accident: isAccident
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
       let isResult = false;
       if(result.status === 'ok'){
        isLoading.value = false;
        isResult = true;
       }else{
        console.log(result.msg);
       }
       loadingSpinner(false);
       return isResult;
    })
    .put();
    
};


const updateAuctionIsDeposit = async (id, IsDeposit) => {
    if (isLoading.value) return;
    
    isLoading.value = true;
    validationErrors.value = {};

    const data = {
        auction: {
            is_deposit: IsDeposit
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
       let isResult = false;
       if(result.status === 'ok'){
        isLoading.value = false;
        isResult = true;
       }else{
        console.log(result.msg);
       }
       return isResult;
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
                    .icon('E') //E:error , W:warning , I:info , Q:question
                    .alert('관리자에게 문의해주세요.');
                }
            })
            .delete();
        }
    })
    .confirm('삭제된 정보는 복구할 수 없습니다.');

};

const getDoneAuctions = async (bidsNumList,page) => {
    const apiList = [];
    apiList.push(`auctions.status:done`);
    //apiList.push(`auctions.bid_id:>:0`);
    return wicac.conn()
        .url(`/api/auctions`)
        .where(apiList)
        .whereOr('auctions.bid_id',`${bidsNumList}`)
        .with([
            'bids',
        ])
        .pageLimit(12)
        .page(`${page}`)
        .callback(function(result) {
            if(result.isSuccess){
                pagination.value = result.rawData.data.meta;
                return result.data;
            }else{
                wica.ntcn(swal)
                .title('오류가 발생하였습니다.')
                .useHtmlText()
                .icon('E') //E:error , W:warning , I:info , Q:question
                .alert('관리자에게 문의해주세요.');
            }
        })
        .get();

        /*
        .addWhere('auctions.bid_id','116')
        .addOrWhere('auctions.bid_id','117')
        */
};


const getIngAuctions = async (bidsNumList,page) => {
    const apiList = [];
    //apiList.push(`auctions.bid_id:>:0`);
    return wicac.conn()
        .url(`/api/auctions`)
        .whereOr('auctions.id',`${bidsNumList}`)
        .with([
            'bids',
        ])
        .pageLimit(12)
        .page(`${page}`)
        .callback(function(result) {
            if(result.isSuccess){
                pagination.value = result.rawData.data.meta;
                console.log('getIngAuctions',result);

                return result.data;
            }else{
                wica.ntcn(swal)
                .title('오류가 발생하였습니다.')
                .useHtmlText()
                .icon('E') //E:error , W:warning , I:info , Q:question
                .alert('관리자에게 문의해주세요.');
            }
        })
        .get();

        /*
        .addWhere('auctions.bid_id','116')
        .addOrWhere('auctions.bid_id','117')
        */
};

const setTacksong = async (id, data) => {

    try {
        loadingSpinner(true);
        const result2 = await wicac.conn()
        .url(`/api/auctions/${id}`)
        .param(data)
        .put();
  
      if (result2.isSuccess) {
        console.log('Auction request sent successfully:', result2);
        loadingSpinner(false);
        location.reload(); // Reload the page on success
      } else {
        console.error('Auction request failed:', result2);
        wica.ntcn(swal)
          .title('오류가 발생하였습니다.')
          .useHtmlText()
          .icon('E') 
          .alert('관리자에게 문의해주세요.');
        loadingSpinner(false);
      }
    } catch (error) {
      console.error('Error during API request:', error);
      loadingSpinner(false);
    }
  };

const setdestddress = async (id,addrInfo) => {
    const data = {
        auction: {
            dest_addr_post : addrInfo.addr_post,
            dest_addr1 : addrInfo.addr1,
            dest_addr2 : addrInfo.addr2
        },
        mode : 'dealerInfo '
    };

    wica.ntcn(swal)
    .title('현 주소지로 탁송신청하시겠습니까?') // 알림 제목
    .icon('Q') //E:error , W:warning , I:info , Q:question
    .callback(async function(result) {

        loadingSpinner(true);

        if(result.isOk){
            wicac.conn()
            .url(`/api/auctions/${id}`)
            .param(data)
            .callback(function(result2) {
                loadingSpinner(false);
                if(result2.isSuccess){
                    wica.ntcn(swal)
                    .icon('I') //E:error , W:warning , I:info , Q:question
                    .callback(function(result3) {
                        if (result3.isOk) {
                            location.reload();
                        }  
                    })
                    .alert('현 주소지로 탁송신청이 되었습니다.');
                }else{
                    wica.ntcn(swal)
                    .title('오류가 발생하였습니다.')
                    .useHtmlText()
                    .icon('E') //E:error , W:warning , I:info , Q:question
                    .alert('관리자에게 문의해주세요.');
                }
            })
            .put();
        }
    }).confirm();

}

const setDeleteFileAuction = async(uuidList) => {
    if (uuidList.endsWith(',')) {
        uuidList = uuidList.slice(0, -1);
    }
    wicac.conn()
    .url(`/api/media/${uuidList}`)
    .log()
    .callback(async function(result) {
    })
    .delete();
}

const getBidsAuction = async (page = 1 , status = "all") => {
    let request = wicac.conn()
    //.log()
    .url('/api/auctions')
    .with(['bids'])
    .page(`${page}`)
    if(status != 'all'){
        if(status == 'bid'){
            request.whereOr('auctions.status','ing,wait');
        } else if(status == 'cnsgnmUnregist'){
            request.addWhere('auctions.status','dlvr');
            request.doesnthave(['auctions.memo_digician:dlvr']);
        } else{
            request.whereOr('auctions.status',`${status}`);
        }
    }
    
    return request.callback(function(result) {
        bidsDataAuction.value = result.data;
        bidPaginationAuction.value = result.rawData.data.meta;
        return result;
    }).get();
    
};

const getAuctionsWithBids = async (page = 1 , status = "all", userId = '', search_text='',bidsIdString = '') => {
    const apiList = [];
    apiList.push(`bids.user_id:`+userId);

    if(status == 'cnsgnmUnregist'){
        let request = wicac.conn()
        .url(`api/auctions?where=bids.user_id:${userId}&where=auctions.bid_id:whereIn:${bidsIdString}
            _and_auctions.status:chosen_and_auctions.dest_addr_post:_null&with=bids,likes
            &search_text=${search_text}&page=${page}`)
        
        return request.callback(function(result) {
            return result;
        }).get();
    }else{
        let request = wicac.conn()
        .url('api/auctions')
        .with(['bids,likes'])
        .search(search_text)
        .page(`${page}`)
        .where(apiList)
        if(status != 'all'){
            if(status == 'bid'){
                request.whereOr('auctions.status','ing,wait');
            } else if(status == 'cnsgnmUnregist'){
            } else{
                request.whereOr('auctions.status',`${status}`);
            }
        }
        
        return request.callback(function(result) {
            return result;
        }).get();
    }


   
    
};

// 예상가 계산 
const checkExpectedPrice = async (data) => {
    return wicac.conn()
        .url(`/api/auctions/checkExpectedPrice`)
        .param(data)
        .callback(function (result) {
            return result; // 예상 가격 데이터 반환
        })
        .post();
};

// 현재 진행중인 경매 갯수 
const allIngCount = async () => {
    return wicac.conn()
        .url(`/api/auctions/allIngCount`)
        .callback(function (result) {
            const allIngCount = result.data;
            return allIngCount;
        })
        .post();
};

// 사고여부 데이터 표시
const isAccident = (id) => {
    let isValue = '';
    switch(id){
      case 0:
        isValue = '사고여부 미진단';
        break;
      case 1:
        isValue = '무사고';
        break;
      case 2:
        isValue = '사고차량';
        break;
      case 3:
        isValue = '무사고 (미래예측 사고차량)';
        break;
      default:
        isValue = '사고여부 미진단';
        break;
    }
    return isValue;
  };


  const sellCheckInputForm = (elementValue, id) => {

    console.log(elementValue);

    const idValue = elementValue;
    const msg = '';
    switch(id){
      case 'region':
        msg = '지역번호를 선택해 주세요.';
      break;
  
      case 'addrPost':
        msg = '우편번호를 입력해 주세요.';
      break;
  
      case 'addrdt':
        msg = '상세주소를 입력해 주세요.';
      break;
  
      case 'bank':
        msg = '은행을 선택해 주세요.';
      break;
  
      case 'account':
        msg = '계좌번호를 입력해 주세요.';
      break;
  
      case 'diagFirstAt':
        msg = '진단희망일1을 입력해 주세요.';
      break;
  
      case 'diagSecondAt':
        msg = '진단희망일2을 입력해 주세요.';
      break;
  
      case 'fileAuctionCarLicense':
        msg = '자동차등록증을 첨부해 주세요.';
      break;
      
    }
  
    if(!idValue){
      wica.ntcn(swal)
      .icon('W')
      .addClassNm('cmm-review-custom')
      .addOption({ padding: 20})
      .callback(function(result) {
      })
      .alert(msg);
      return;
    }
  
  }

  // 공매 엑셀 파일 확인
  const checkAuctionEntryPublic = async (file) => {

    console.log('공매 엑셀 파일 확인',file);

    const formData = new FormData();
    if(file){
        formData.append('file', file);
    }

    return wicac.conn()
    .url(`/api/auctions/entryPublic`)
    .param(formData)
    .multipart()
    .callback(function (result) {
      return result;
    })
    .post();
  }


  const getNiceDnrHistory = async (ownerNm, vhrNo) => {
    return wicac.conn()
    .url(`/api/getNiceDnrHistory`)
    .param({
        "owner":ownerNm, 
        "no":vhrNo
    })
    .callback(function (result) {
      return result;
    })
    .get();
  }


  const checkBusinessStatus = async (data) => {

    loadingSpinner(true);

    return wicac.conn()
    .url(`/api/check-business`)
    .param(data)
    .callback(function (result) {
      loadingSpinner(false);
      return result;
    })
    .post();
  }

  const getCarHistoryCrash = async (carNumber) => {
    return wicac.conn()
    .url(`/api/carHistoryCrash`)
    .param({
        "car_no":carNumber
    })
    .callback(function (result) {
      return result;
    })
    .get();
  }

  const getCertificationData = async (carNumber) => {
    return wicac.conn()
    .url(`/api/get-certification-data`)
    .param({
        "carNumber":carNumber
    })
    .callback(function (result) {
      return result;
    })
    .post();
  }

  const clearCertificationData = async (carNumber) => {
    return wicac.conn()
    .url(`/api/clear-certification-data`)
    .param({
        "carNumber":carNumber
    })
    .callback(function (result) {
      return result;
    })
    .post();
  }

  const nameChangeStatus = async (auctionId) => {
    return wicac.conn()
    .url(`/api/name-change-status`)
    .param({
        "auction_id":auctionId
    })
    .callback(function (result) {
      return result;
    })
    .get();
  }

  const nameChangeFileUpload = async (auctionId, file) => {
    return wicac.conn()
    .url(`/api/auctions/${auctionId}/name-change-file-upload`)
    .param({
        "nameChange_file":file
    })
    .multipart()
    .callback(function (result) {
      return result;
    })
    .post();
  }

    return {
        getAuctionsByDealerLike,
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
        updateAuction,
        getAuctionsByDealer,
        getDoneAuctions,
        setdestddress,
        setTacksong,
        getBidsAuction,
        bidsDataAuction,
        bidPaginationAuction,
        getAuctionsWithBids,
        checkExpectedPrice,
        updateAuctionIsDeposit,
        allIngCount,
        isAccident,
        sellCheckInputForm,
        getIngAuctions,
        checkAuctionEntryPublic,
        getNiceDnrHistory,
        checkBusinessStatus,
        getCarHistoryCrash,
        getCertificationData,
        clearCertificationData,
        nameChangeStatus,
        nameChangeFileUpload
    };
    
}