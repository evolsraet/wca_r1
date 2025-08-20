export default function () {
    return {
        // 데이터 속성 정의
        loading: false,
        owner: '',
        no: '',
        error: '',
        
        async submitForm() {
            if (!this.validateInputs()) {
                return false;
            }

            this.loading = true;
            this.error = '';

            try {
                const response = await this.$store.api.post('/api/sell/car-info/lookup', {
                    owner: this.owner,
                    no: this.no
                });

                this.loading = false;

                // 응답 상태 확인
                if (!response || response.status !== 200) {
                    this.handleError(response);
                    return false;
                }

                const responseData = response.data;
                
                // 서버에서 실패 응답인 경우
                if (responseData.status === 'fail' || responseData.success === false) {
                    this.error = responseData.message || '차량 정보 조회에 실패했습니다.';
                    return false;
                }

                return this.handleSuccess(responseData);

            } catch (error) {
                this.loading = false;
                this.handleNetworkError(error);
                return false;
            }
        },

        validateInputs() {
            if (!this.owner.trim() || !this.no.trim()) {
                this.error = '소유자와 차량번호를 모두 입력해주세요.';
                return false;
            }
            return true;
        },

        handleError(response) {
            if (response && response.data) {
                // 백엔드에서 온 메시지 그대로 사용 (Nice DNR resultMsg 포함)
                this.error = response.data.message || '서버에서 오류가 발생했습니다.';
                
                // HTTP 상태 코드별 기본 처리만 유지
                if (response.status >= 500 && !response.data.message) {
                    this.error = '서버에 일시적인 문제가 발생했습니다. 잠시 후 다시 시도해주세요.';
                }
            } else {
                this.error = '차량 정보를 찾을 수 없습니다.';
            }
        },

        handleNetworkError(error) {
            console.error('Car info lookup error:', error);
            
            if (error.code === 'NETWORK_ERROR' || !navigator.onLine) {
                this.error = '네트워크 연결을 확인해주세요.';
            } else if (error.code === 'TIMEOUT') {
                this.error = '요청이 시간 초과되었습니다. 다시 시도해주세요.';
            } else {
                this.error = error.response?.data?.message || '차량 정보 조회 중 오류가 발생했습니다.';
            }
        },

        handleSuccess(responseData) {
            const gradeList = responseData.data?.gradeList;

            if (gradeList && gradeList.length > 1) {
                // 등급 선택 모달 표시
                Alpine.store('modal').showHtmlFromUrl('/v2/components/modals/carGradeSelector', {
                    id: 'gradeSelectModal',
                    title: '차량 등급을 선택해주세요',
                    size: 'modal-dialog-centered',
                    showFooter: false,
                    initAlpine: true
                }, {
                    content: { 
                        gradeList: gradeList,
                        owner: this.owner,
                        no: this.no
                    }
                });
            } else {
                // 차량 정보 페이지로 이동
                window.location.href = `/v2/sell/car_info?owner=${encodeURIComponent(this.owner)}&no=${encodeURIComponent(this.no)}`;
            }

            return true;
        }
    }
};

