export default function () {
    return {
        bidsCount: 0,
        likesCount: 0,
        bidAmount: null,
        init() {
            const data = window.modalData.content;
            console.log('auctionDealerIngModal init');
            console.log(data);

            this.bidsCount = data.bids.length;
            this.likesCount = data.likes.length;

        },
        submit() {
            console.log('submit');

            // this.bidAmount;
            console.log(this.bidAmount);

        },
        openSuccessModal() {

            // 입력값 체크 참고
            /*
            const maxAmount = auctionDetail.value?.data?.middle_prices?.max / 10000;
            const minAmount = auctionDetail.value?.data?.middle_prices?.min / 10000;
            const avgAmount = auctionDetail.value?.data?.middle_prices?.avg / 10000;
            const carPriceNowWhole = auctionDetail.value.data.car_price_now_whole / 10000;
        
            const amountValue = amount.value;
        
            console.log('amountValue',amountValue);
            console.log('carPriceNowWhole',carPriceNowWhole);
            console.log('minAmount',minAmount);
            console.log('maxAmount',maxAmount);
        
            if(amountValue < carPriceNowWhole * 0.6){
                wica.ntcn(swal).icon('S').title('도매가보다 60%가 낮은 금액은 입찰이 불가능합니다.').fire();
                return;
            }
        
            if(auctionDetail.value?.data?.bids_count < 3){
                if(amountValue < minAmount){
                wica.ntcn(swal).icon('S').title('경매 최소 금액을 넘어갑니다.').fire();
                return;
                }
            }else{
                if(amountValue > maxAmount){
                wica.ntcn(swal).icon('S').title('경매 최대 금액을 넘어갑니다.').fire();
                return;
                }
                if(amountValue < minAmount){
                wica.ntcn(swal).icon('S').title('경매 최소 금액을 넘어갑니다.').fire();
                return;
                }
            }
            */
            
            



            Alpine.store('modal').showHtmlFromUrl('/v2/components/modals/auctionDealerIngModalSuccess', {
                id: 'auctionDealerIngModalSuccess',
                title: '입찰하기',
                size: 'modal-dialog-centered',
                showFooter: false,
            }, {
                content: {
                    bidAmount: this.bidAmount
                }
            });
        }
    }
}