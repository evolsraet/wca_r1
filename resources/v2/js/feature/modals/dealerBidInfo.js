export default function () {
    return {
        data:{},
        auction:{},
        init() {
            this.data = window.modalData.content.data;
            this.auction = window.modalData.content.auction;
            // console.log('data', this.data);
        },

        submit() {
    
            console.log('dealerBidInfo submit');

            Alpine.store('auctionEvent').updateAuction(this.auction.id, {
                auction: {
                    status: 'chosen',
                    choice_at: new Date().toISOString(),
                    final_price: this.data.price,
                    bid_id: this.data.id, 
                }
            }).then(() => {

                this.closeModal();
            }).catch(() => {
                Alpine.store('swal').show({
                    title: '딜러 선택 중 오류가 발생하였습니다.',
                    text: '딜러 선택 중 오류가 발생하였습니다.',
                    icon: 'error',
                    confirmButtonText: '확인',
                });
            });
        },

        closeModal() {
            Alpine.store('modal').close('dealerBidInfo');
        }
    };
}
