import { api } from '../../util/axios.js';

export default function () {
  return {
    auction: null,
    diag: null,

    async init() {
      try {
        const hashid = window.hashid;
        const carNumber = window.carNumber;

        // API 호출
        const auctionRes = await api.get(`/api/auctions/${hashid}`, {
          with: 'bids,reviews,likes',
          paginate: '12',
        });
        const diagRes = await api.get(`/api/diagRequest?diag_car_no=${carNumber}`);

        // Store 등록 (정적 데이터로)
        Alpine.store('details', {
          ready: true, // <- 상태 플래그 추가 (다른 컴포넌트에서 확인용)
          auctionData: auctionRes?.data?.data ?? null,
          diag: diagRes?.data?.data ?? null,
        });

        // 로컬 상태 반영
        this.auction = Alpine.store('details')?.auctionData;
        this.diag = Alpine.store('details')?.diag;

        // window에도 필요 시 복사
        window.auctionData = this.auction;
        window.diagData = this.diag;

        console.log('✅ store 등록 완료:', Alpine.store('details'));
      } catch (err) {
        console.error('❌ API 호출 실패:', err);
      }
    },
  };
}