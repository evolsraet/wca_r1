export default function () {
    return {
        init() {
            console.log('authModal');
        },

      selected: '',
      options: ['카카오 인증', '네이버 인증', '통신사 인증'],
  
      submit() {
        if (!this.selected) {
          alert('인증 수단을 선택해주세요.');
          return;
        }
  
        console.log('선택한 인증 수단:', this.selected);
  
        // 여기서 부모 페이지에 emit하거나 fetch로 전달 가능
        // 또는 모달 닫기
        // modal.close(); // 동적 모달 닫기 함수
      }
    };
}
