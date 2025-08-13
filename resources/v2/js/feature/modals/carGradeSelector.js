export default function () {
    return {
        no: '',
        owner: '',
        grades: [],
        loading: false,
        
        init() {
            this.loadData();
        },
        
        // gradeList 데이터 로딩
        loadData() {
            if (window.modalData && window.modalData.content && window.modalData.content.gradeList) {
                this.grades = window.modalData.content.gradeList;
                this.owner = window.modalData.content.owner;
                this.no = window.modalData.content.no;
            } else {
                this.grades = [];
            }
        },
        
        // 등급 선택 처리
        async selectGrade(grade) {
            if (!grade.bpNo || !grade.gradeId) {
                alert('차량 정보가 올바르지 않습니다.');
                return;
            }
            
            // 모달 닫기
            this.closeModal();

            const response = await this.$store.api.post('/api/sell/car-info/grade-selection', {
                owner: this.owner,
                no: this.no,
                gradeId: grade.gradeId,
                bpNo: grade.bpNo,
            });
            
            window.location.href = `/v2/sell/car_info?owner=${encodeURIComponent(this.owner)}&no=${encodeURIComponent(this.no)}&gradeId=${encodeURIComponent(grade.gradeId)}`;
        },
        
        // 모달 닫기
        closeModal() {
            Alpine.store('modal').close('gradeSelectModal');
        }
    };
}