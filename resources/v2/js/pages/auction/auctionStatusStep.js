export default function (statusDate='ask') {
    return {
        steps: Alpine.store('auctionStatus').stepOrder,
        status: statusDate ?? 'ask',
    
        getCurrentStep() {
          return this.steps.indexOf(this.status);
        },
    
        getLabel(key) {
          return Alpine.store('auctionStatus').labelMap[key] ?? '알 수 없음';
        },
    
        isActive(stepKey) {
          return this.getCurrentStep() >= this.steps.indexOf(stepKey);
        },
    
        getClass(stepKey) {
          return Alpine.store('auctionStatus').classMap[stepKey] ?? 'text-bg-light text-dark';
        }
    }
}