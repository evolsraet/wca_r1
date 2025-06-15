const auctionStatus = {

    stepOrder: ['ask', 'diag', 'ing', 'wait', 'chosen', 'dlvr', 'dlvr_done', 'done'],
  
    labelMap: window.auctionStatus.status,
    classMap: window.auctionStatus.classMap,
  
    get(status) {
      return {
        label: this.labelMap[status] ?? '알 수 없음',
        class: this.classMap[status] ?? 'text-bg-light text-dark',
      };
    }
  };
  
  export default auctionStatus;
