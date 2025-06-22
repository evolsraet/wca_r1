export default function () {
    return {
        auction: window.auction,
        nameChangeFileUpload: null,
        errors: [],
        init(auction) {
            this.auction = auction;
            console.log('nameChange init', this.auction);
            this.initWithWatch();
        },

        initWithWatch() {
            const check = setInterval(() => {
                if (window.auction && window.auction.id) {
                    this.auction = window.auction;
                    clearInterval(check);
                }
            }, 50);
        },

        nameChangeFileUpload() {

            const file = document.querySelector('input[name="file_auction_name_change"]').files[0];
            console.log('file', file);
            console.log('this.auction.id', this.auction.id);

            if(file) {
                Alpine.store('auctionEvent').nameChangeFileUpload(this.auction.id, file).then(res => {
                    console.log('res', res);
                });
            } else {
                Alpine.store('swal').fire({
                    title: '첨부 실패',
                    text: '파일을 첨부해주세요.',
                    icon: 'error',
                    confirmButtonText: '확인'
                });
            }
        }
    }
}