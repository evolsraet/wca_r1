export default function () {
    return {
        loading: false,
        init() {
            console.log('sell index init');
            console.log('window.userCarInfo:', window.userCarInfo);
            if (window.userCarInfo && window.userCarInfo.length > 0) {
                this.getUserCarInfoModal();
            } else {
                console.log('차량 정보가 없어서 모달을 표시하지 않습니다.');
            }
        },
        
        // 폼 제출 처리
        submitForm(event) {
            console.log('폼 제출 시작');
            this.loading = true;
            
            // 기본 폼 제출을 계속 진행
            // loading은 페이지가 이동되기 전까지 true로 유지됨
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