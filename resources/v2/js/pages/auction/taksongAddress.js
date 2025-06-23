export default function () {
    return {
        auction: window.auction,
        addr_post: '',
        addr1: '',
        addr2: '',
        init(auction) {
            console.log('taksongAddress init');
            // this.auction = window.auction;
            console.log('taksongAddress auction', window.auction);
        },

        initWithWatch() {
            const check = setInterval(() => {
              if (window.auction && window.auction.id) {
                this.auction = window.auction;

                this.addr_post = this.auction.win_bid.user.dealer.company_post;
                this.addr1 = this.auction.win_bid.user.dealer.company_addr1;
                this.addr2 = this.auction.win_bid.user.dealer.company_addr2;

                clearInterval(check);
              }
            }, 50);

            window.addEventListener('addressBookSelect', (e) => {
                const { address } = e.detail;
            
                this.addr_post = address.addr_post;
                this.addr1 = address.addr1;
                this.addr2 = address.addr2;
            
                console.log('주소록에서 선택된 주소:', address);
            });
        },

        submit() {

            const data = {
                auction : {
                    dest_addr_post : this.addr_post,
                    dest_addr1 : this.addr1,
                    dest_addr2 : this.addr2
                },
                mode : 'dealerInfo'
            }

            console.log('taksongAddress data', data);
            console.log('taksongAddress auction', this.auction.id);

    
            Alpine.store('swal').fire({
                title: '탁송하기',
                text: '탁송 신청 정보, 탁송 주소지를 다시한번 확인해주세요',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: '취소',
                confirmButtonText: '확인',
            }).then((result) => {
                if (result.isConfirmed) {
                    // 탁송 신청 
                    
                    Alpine.store('auctionEvent').updateAuction(this.auction.id, data).then(() => {
                        console.log('taksongAddress res');
                    }).catch(() => {
                        Alpine.store('swal').show({
                            title: '탁송 신청 중 오류가 발생하였습니다.',
                            text: '탁송 신청 중 오류가 발생하였습니다.',
                            icon: 'error',
                            confirmButtonText: '확인',
                        });
                    });
                    
                }
            });

        },

        openAddressBookModal() {
            console.log('openAddressBookModal');

            Alpine.store(`modal`).showHtmlFromUrl(`/v2/components/modals/addressBook`, 
            { id: 'addressBookModal', title: `주소록`, size: `modal-lg modal-dialog-centered`, showFooter: false});

        }
    }
}