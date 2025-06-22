import Alpine from 'alpinejs';
import useBidStore from '../../util/bids.js';
Alpine.store('bid', useBidStore());

export default function () {
    return {
        init() {
            console.log('init');
        },
        cancelBid(id) {
            console.log('cancelBid', id);
            Alpine.store('bid').cancelBid(id).then(res => {
                console.log('res', res);

                if(res.success) {
                    Alpine.store('swal').fire({
                        title: '입찰 취소 성공',
                        text: res.message,
                        icon: 'success',
                        confirmButtonText: '확인'
                    });

                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);

                } else {

                    Alpine.store('swal').fire({
                        title: '입찰 취소 실패',
                        text: res.message,
                        icon: 'error',
                        confirmButtonText: '확인'
                    });

                }

            });
        }
    };
}