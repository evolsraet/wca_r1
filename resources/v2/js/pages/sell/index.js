export default function () {
    return {
        // 데이터 속성 정의
        loading: false,
        owner: '',
        no: '',
        
        async submitForm() {
            if (!this.owner.trim() || !this.no.trim()) {
                this.error = '소유자와 차량번호를 모두 입력해주세요.';
                return false;
            }

            this.loading = true;

            const response = await this.$store.api.post('/api/sell/car-info/lookup', {
                owner: this.owner,
                no: this.no
            });

            this.loading = false;

            if( response == 'undefined' || response.status !== 200 ) {
                this.error = response.data?.message ?? '차량 정보를 찾을 수 없습니다.';
                return false;
            }

            const gradeList = response.data.data.gradeList;

            if (gradeList && gradeList.length > 1) {
                
                // 등급 선택 모달 표시 (데이터를 세 번째 파라미터로 전달)
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
                window.location.href = `/v2/sell/car_info?owner=${encodeURIComponent(this.owner)}&no=${encodeURIComponent(this.no)}`;
            }

            return false;
        },
    }
};

