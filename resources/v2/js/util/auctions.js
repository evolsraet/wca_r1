
// 경매상태 확인
const auctionStatus = {
    // 경매상태바 순서지정
    stepOrder: ['ask', 'diag', 'ing', 'wait', 'chosen', 'dlvr', 'dlvr_done', 'done'],
    // 경매상태바 라벨지정
    labelMap: window.auctionStatus?.status,
    // 경매상태바 클래스지정 (색상)
    classMap: window.auctionStatus?.classMap,

    get(status) {
      return {
        label: this.labelMap[status] ?? '알 수 없음',
        class: this.classMap[status] ?? 'text-bg-light text-dark',
      };
    }
};  
export default auctionStatus;


// 경매 카운트다운
window.addEventListener('start-countdown', (e) => {
    const { finalAt } = e.detail;
    if (!finalAt) return;

    const target = document.querySelector('[data-timer]');
    if (!target) return;

    let intervalId;

    const update = () => {
    const now = new Date();
    const end = new Date(finalAt);
    const diff = end - now;

    if (diff <= 0) {
        target.textContent = '00:00:00';
        clearInterval(intervalId);
        return;
    }

    const h = String(Math.floor(diff / 1000 / 60 / 60)).padStart(2, '0');
    const m = String(Math.floor(diff / 1000 / 60) % 60).padStart(2, '0');
    const s = String(Math.floor(diff / 1000) % 60).padStart(2, '0');

    target.textContent = `${h}:${m}:${s}`;
    };

    update(); // 최초 실행
    intervalId = setInterval(update, 100);
});
