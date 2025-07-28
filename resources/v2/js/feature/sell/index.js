export default function () {
    return {
        init() {
            console.log('sell index init');
            console.log('window.userCarInfo:', window.userCarInfo);
            if (window.userCarInfo && window.userCarInfo.length > 0) {
                this.getUserCarInfoModal();
            } else {
                console.log('차량 정보가 없어서 모달을 표시하지 않습니다.');
            }
        },
        getUserCarInfoModal() {
            console.log('getUserCarInfoModal 호출');
            Alpine.store(`modal`).showHtmlFromUrl('/v2/components/modals/userCarInfoModalContent', {
                id: 'userCarInfoModalContent',
                title: '차량 정보 조회',
                size: 'modal-dialog-centered',
                showFooter: false,
                initAlpine: true // Alpine 초기화 명시적으로 활성화
            }).then(() => {
                console.log('모달 로딩 완료');
            }).catch(error => {
                console.error('모달 로딩 오류:', error);
            });
        }
    };
}