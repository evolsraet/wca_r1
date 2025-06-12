import { api } from '../../util/axios';

export default function () {
    return {
        estimatedPriceInTenThousandWon: 0,
        carInfo: {},
        error: null,
        init() {
            
            if(localStorage.getItem('carInfo') === null){
                localStorage.setItem('carInfo', JSON.stringify({
                    ...window.carInfo,
                    timestamp: Date.now()
                }));
            }

            this.carInfo = JSON.parse(localStorage.getItem('carInfo'));
            this.estimatedPriceInTenThousandWon = JSON.parse(localStorage.getItem('estimatedPrice'))?.value;

            this.openUsageNotice();
        },
        async getResult() {
            console.log(this.carInfo);
        },
        async refreshCarInfo() {
            const owner = this.carInfo.owner_name;
            const no = this.carInfo.car_no;
        
            try {
                const response = await api.post('/api/auctions/carInfo', {
                    owner: owner,
                    no: no,
                    forceRefresh: true
                });
        
                const result = response.data;
        
                if (result.status === 'is_not_count') {
                    Alpine.store('swal').fire({
                        title: '차량 정보 갱신 실패',
                        text: result.message || '차량 정보 갱신 실패',
                        icon: 'error',
                        confirmButtonText: '확인'
                    });
                    return;
                }
        
                // 캐시 갱신 제한 메시지 처리 (message 존재 시)
                if (result.message === '갱신은 하루 1회만 가능합니다.') {
                    Alpine.store('swal').fire({
                        title: '차량 정보 갱신 실패',
                        text: result.message,
                        icon: 'error',
                        confirmButtonText: '확인'
                    });
                    return;
                }
        
                Alpine.store('swal').fire({
                    title: '차량 정보 갱신 성공',
                    text: '차량 정보가 갱신되었습니다.',
                    icon: 'success',
                    timer: 2000
                });
        
                setTimeout(() => {
                    location.reload();
                }, 2000);
        
            } catch (error) {
                console.error('갱신 중 오류', error);
        
                Alpine.store('swal').fire({
                    title: '차량 정보 갱신 실패',
                    text: error.response?.data?.message || '서버 오류 또는 제한된 요청입니다.',
                    icon: 'error',
                    confirmButtonText: '확인'
                });
            }
        },
        openCurrentPriceModal() {
            const estimatedPrice = localStorage.getItem('estimatedPrice');

            if (estimatedPrice) {
                const estimatedPriceData = JSON.parse(estimatedPrice);
                
                console.log('estimatedPriceData.value', estimatedPriceData.value);
        
                Alpine.store(`modal`).showHtmlFromUrl('/v2/components/modals/carPriceResultModal', {
                    id: 'carPriceResultModal',
                    title: '예상 가격 측정',
                    showFooter: false,
                    data: {
                        estimatedPriceInTenThousandWon: estimatedPriceData.value,
                        carNo: estimatedPriceData.carNo
                    }
                });
        
                return;
            }
        
            Alpine.store(`modal`).showHtmlFromUrl('/v2/components/modals/currentPrice', {
                id: 'currentPrice',
                title: '내 차, 예상가격을 확인합니다',
                showFooter: false,
                data: {
                    carInfo: window.carInfo
                },
                onResult: (result) => {
                    console.log('result?', result);
                }
            });
        },
        openAuctionModal() {
            if (!isLogin) {

                Alpine.store('swal').fire({
                    title: '로그인 필요',
                    text: '로그인을 하면 경매 신청이 가능합니다.',
                    icon: 'warning',
                    confirmButtonText: '확인'
                }).then(() => {
                    window.location.href = '/v2/login';
                }).catch(() => {
                    window.location.href = '/v2/login';
                });

                return;
            }
            Alpine.store(`modal`).showHtmlFromUrl('/v2/components/modals/auctionProcessSteps', {
                id: 'auctionProcessSteps',
                title: '경매 진행 순서 안내',
                showFooter: false
            });
        },
        openUsageNotice() {
            Alpine.store(`modal`).showHtmlFromUrl('/v2/docs/usageNotice?raw=1', {
                id: 'usageNotice',
                title: '차량출품 조건 및 유의사항',
                showFooter: false
            });
        }
    };
}