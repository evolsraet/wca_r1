import Alpine from 'alpinejs';
import useBidStore from '../../util/bids.js';
Alpine.store('bid', useBidStore());

export default function () {
    return {
        init() {
            console.log('activeAuctionDealers init');
        },
        // 경매 재시작
        restartAuction(auction) {
            console.log('restartAuction', auction?.id);

            if(auction?.status == 'wait') {
                Alpine.store('swal').fire({
                    title: '경매 재시작',
                    text: '경매를 재시작하시겠습니까?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: '확인',
                    cancelButtonColor: '#d33',
                    cancelButtonText: '취소',
                }).then(result => {
                    if (result.isConfirmed) {
                        Alpine.store('auctionEvent').reauction(auction?.id).then(res => {
                            console.log('res', res);
                        });
                    }
                });
            }else{
                Alpine.store('swal').fire({
                    title: '경매 재시작 불가',
                    text: '경매 재시작은 선택 대기상태에서만 가능합니다.',
                    icon: 'error',
                });
            }

        },
        // 경매 취소
        cancelAuction(auction) {
            console.log('cancelAuction', auction?.id);

            Alpine.store('swal').fire({
                title: '경매 취소',
                text: '입력부분에 "취소"라고 입력후 확인버튼을 눌러주세요.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: '확인',
                cancelButtonColor: '#d33',
                cancelButtonText: '취소',
                input: 'text',
                inputPlaceholder: '취소',
                inputValidator: (value) => {
                    return value === '취소' ? undefined : '정확히 "취소"라고 입력해야 합니다.';
                }
            }).then(result => {
                if (result.isConfirmed) {
                    Alpine.store('auctionEvent').updateAuctionStatus(auction?.id, 'cancel').then(res => {
                        console.log('res', res);
                    });
                }
            });


        },
        
    }
}