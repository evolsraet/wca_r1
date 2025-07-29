export default function () {
    return {
        // 폼 데이터
        form: {
            owner: '',
            no: ''
        },
        
        // 에러 상태
        errors: {},
        
        // 로딩 상태
        isLoading: false,
        
        init() {
            console.log('sell index init');
            console.log('window.userCarInfo:', window.userCarInfo);
            if (window.userCarInfo && window.userCarInfo.length > 0) {
                this.getUserCarInfoModal();
            } else {
                console.log('차량 정보가 없어서 모달을 표시하지 않습니다.');
            }
        },
        
        // 폼 제출 함수
        async submitForm() {
            console.log('폼 제출 시작:', this.form);
            
            // 기본 유효성 검사
            this.errors = {};
            if (!this.form.owner.trim()) {
                this.errors.owner = ['소유자를 입력해주세요.'];
                return;
            }
            if (!this.form.no.trim()) {
                this.errors.no = ['차량번호를 입력해주세요.'];
                return;
            }
            
            this.isLoading = true;
            
            try {
                // 기존 폼 제출 방식 사용 (CSRF 토큰 포함)
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/v2/sell/result';
                
                // CSRF 토큰 추가
                const token = document.querySelector('meta[name="csrf-token"]')?.content;
                if (token) {
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = token;
                    form.appendChild(csrfInput);
                }
                
                // 폼 데이터 추가
                Object.entries(this.form).forEach(([key, value]) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    form.appendChild(input);
                });
                
                // 폼 제출
                document.body.appendChild(form);
                form.submit();
                
            } catch (error) {
                console.error('폼 제출 오류:', error);
                this.isLoading = false;
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