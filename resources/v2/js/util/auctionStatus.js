const auctionStatus = {
    labelMap: {
      cancel: '취소',
      done: '경매완료',
      chosen: '선택완료',
      wait: '선택대기',
      ing: '경매진행',
      diag: '진단대기',
      dlvr: '탁송중',
      ask: '신청완료',
    },
  
    classMap: {
      cancel: 'text-bg-danger',
      done: 'text-bg-secondary',
      chosen: 'text-bg-chosen',
      wait: 'text-bg-warning',
      ing: 'text-bg-info',
      diag: 'text-bg-dark',
      dlvr: 'text-bg-dlvr',
      ask: 'text-bg-success',
    },
  
    get(status) {
      return {
        label: this.labelMap[status] ?? '알 수 없음',
        class: this.classMap[status] ?? 'text-bg-light text-dark',
      };
    }
  };
  
  export default auctionStatus;