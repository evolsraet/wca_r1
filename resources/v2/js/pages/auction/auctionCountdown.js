export default function (endTimeStr) {
    return {
        timeLeft: '',
        endTime: new Date(endTimeStr),
        intervalId: null,

        init() {
            // console.log('endTimeStr???', endTimeStr);
        },
    
        start() {

          this.updateTime();
          this.intervalId = setInterval(() => this.updateTime(), 1000);
        },
    
        updateTime() {
            const now = new Date();
            const diffMs = this.endTime - now; // 남은 시간 (ms)
          
            if (diffMs <= 0) {
              this.timeLeft = '00:00:00';
              clearInterval(this.intervalId);
              return;
            }
          
            const totalSeconds = Math.floor(diffMs / 1000);
            const days = Math.floor(totalSeconds / 86400);
            const hours = Math.floor((totalSeconds % 86400) / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;
          
            const h = String(hours).padStart(2, '0');
            const m = String(minutes).padStart(2, '0');
            const s = String(seconds).padStart(2, '0');
          
            // 1일 이상 남았으면 '1일 05:23:14' 형태로 표시
            this.timeLeft = days > 0 ? `${days}일 ${h}:${m}:${s}` : `${h}:${m}:${s}`;
        }
    };
}