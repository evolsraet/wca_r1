export default function () {
    return {
        init() {
            console.log('sell index init');
            console.log(window.userCarInfo);
            if (window.userCarInfo.length > 0) {
                this.getUserCarInfoModal();
            }
        },
        getUserCarInfoModal() {
            Alpine.store(`modal`).showHtmlFromUrl('/v2/components/modals/userCarInfoModalContent', {
                id: 'userCarInfoModalContent',
                title: '차량 정보 조회',
                size: 'modal-dialog-centered',
                showFooter: false,
            });
        }
    };
}