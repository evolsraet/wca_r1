export function setupCountdownListener() {
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
  }