// 경매 카운트다운
window.addEventListener('start-countdown', (e) => {
  const { finalAt } = e.detail;
  const targets = document.querySelectorAll('[data-timer]');
  if (!finalAt) {
    // finalAt이 없으면 타이머 내용 비우기
    targets.forEach(target => {
      target.textContent = '';
    });
    return;
  }
  if (!targets.length) return;

  let intervalId;

  const update = () => {
    const now = new Date();
    const end = new Date(finalAt);
    const diff = end - now;

    let text;
    if (diff <= 0) {
      text = '00:00:00';
      clearInterval(intervalId);
    } else {
      const totalSeconds = Math.floor(diff / 1000);
      const days = Math.floor(totalSeconds / (60 * 60 * 24));
      const hours = Math.floor((totalSeconds % (60 * 60 * 24)) / (60 * 60));
      const minutes = Math.floor((totalSeconds % (60 * 60)) / 60);
      const seconds = totalSeconds % 60;

      const h = String(hours).padStart(2, '0');
      const m = String(minutes).padStart(2, '0');
      const s = String(seconds).padStart(2, '0');

      text = days > 0 ? `${days}일 ${h}:${m}:${s}` : `${h}:${m}:${s}`;
    }

    targets.forEach(target => {
      target.textContent = text;
    });
  };

  update();
  intervalId = setInterval(update, 1000);
});


// 경매상태 확인
export const auctionStatus = {
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


export const auctionEvent = {

  // 경매 재시작
  reauction: async (auctionId) => {

    await Alpine.store('api').put(`/api/auctions/${auctionId}`, {
        mode: 'reauction'
    }).then(res => {
        console.log('res', res);

        if(res.statusText == 'OK') {

          Alpine.store('swal').fire({
              title: '경매 재시작 성공',
              text: res.message,
              icon: 'success',
              confirmButtonText: '확인'
          });

          setTimeout(() => {
              window.location.reload();
          }, 1000);
          
          return res;
        }

        if(res.isError) {
            Alpine.store('swal').fire({
                title: '경매 재시작 실패',
                text: res.message,
                icon: 'error',
                confirmButtonText: '확인'
            });
        }

    });
  },
  // 경매 상태 업데이트
  updateAuctionStatus: async (auctionId, status) => {
    await Alpine.store('api').put(`/api/auctions/${auctionId}`, {
      auction: {
        status: status
      }
    }).then(res => {
        console.log('res', res);

        if(res.statusText == 'OK') {
            Alpine.store('swal').fire({
                title: '경매 상태 업데이트 성공',
                text: res.message,
                icon: 'success',
                confirmButtonText: '확인'
            });

            setTimeout(() => {
                window.location.reload();
            }, 1000);

            return res;
        }

        if(res.isError) {
            Alpine.store('swal').fire({
                title: '경매 상태 업데이트 실패',
                text: res.message,
                icon: 'error',
                confirmButtonText: '확인'
            });
            return res;
        }

    });
  },

  // 내용 업데이트 
  updateAuction: async (auctionId, data) => { 

    await Alpine.store('api').put(`/api/auctions/${auctionId}`, data).then(res => {
      console.log('res', res);

      if(res.statusText == 'OK') {
        Alpine.store('swal').fire({
          title: '업데이트 성공',
          text: res.message,
          icon: 'success',
          confirmButtonText: '확인'
        });

        setTimeout(() => {
          window.location.reload();
        }, 1000);

        return res;
      }
    });

  },

  createAuction: async (auction) => {
    try {
      const res = await Alpine.store('api').post(`/api/auctions`, auction);
      console.log('res', res);

      if(res.statusText == 'OK') {  
        // Alpine.store('swal').fire({
        //   title: '등록 성공',
        //   text: res.message,
        //   icon: 'success',
        //   confirmButtonText: '확인'
        // });

        return res;
      }

      if(res.isError) {
        // Alpine.store('swal').fire({
        //   title: '등록 실패',
        //   text: res.message,
        //   icon: 'error',
        //   confirmButtonText: '확인'
        // });
        // return res;
      }
      
      return res;
    } catch (error) {
      // console.error('경매 등록 오류:', error);
      // Alpine.store('swal').fire({
      //   title: '등록 실패',
      //   text: '경매 등록 중 오류가 발생했습니다.',
      //   icon: 'error',
      //   confirmButtonText: '확인'
      // });
      // return {
      //   isError: true,
      //   message: '경매 등록 중 오류가 발생했습니다.'
      // };
    }
  },

  updateAuctionAdmin: async (auctionId, auction) => {

    const auctionForm = {
      auction: {
        bank : auction.bank,
        account : auction.account,
        car_no: auction.car_no,
        owner_name: auction.owner_name,
        bank: auction.bank,
        account: auction.account,
        memo: auction.memo,
        region:auction.region,
        addr_post: auction.addr_post,
        status : auction.status,
        addr1: auction.addr1,
        addr2: auction.addr2,
        success_fee: auction.success_fee,
        diag_fee: auction.diag_fee,
        diag_first_at: auction.diag_first_at,
        diag_second_at: auction.diag_second_at,
        total_fee: auction.total_fee,
        hope_price: auction.hope_price,
        final_price: auction.final_price,
        is_biz: auction.isBizChecked,
        dest_addr_post: auction.dest_addr_post,
        dest_addr1: auction.dest_addr1,
        dest_addr2: auction.dest_addr2,
      }
    }

    if(auction.choice_at){
      auctionForm.auction.choice_at = auction.choice_at;
    }

    if(auction.done_at){
      auctionForm.auction.done_at = auction.done_at;
    }

    if(auction.taksong_wish_at){
      auctionForm.auction.taksong_wish_at = auction.taksong_wish_at;
    }

    const formData = new FormData();

    if(auction.status == 'diag'){
        auctionForm.auction.status = 'ask';

        auctionForm.mode = 'diag';
        formData.append('mode', auctionForm.mode);
    }

    formData.append('auction', JSON.stringify(auctionForm.auction));
    if(auction.file_auction_proxy){
        formData.append('file_auction_proxy', auction.file_auction_proxy);
    }
    if(auction.file_auction_owner){
        formData.append('file_auction_owner', auction.file_auction_owner);
    }
    if(auction.file_auction_car_license){
        formData.append('file_auction_car_license', auction.file_auction_car_license);
    }

    await Alpine.store('api').put(`/api/auctions/${auctionId}`, formData).then(res => {
      console.log('res', res);

      if(res.statusText == 'OK') {
        Alpine.store('swal').fire({
          title: '업데이트 성공',
          text: res.message,
          icon: 'success',
          confirmButtonText: '확인'
        });

        setTimeout(() => {
          window.location.reload();
        }, 1000);

        return res;
      }

      if(res.isError) {
        Alpine.store('swal').fire({
          title: '업데이트 실패',
          text: res.message,
          icon: 'error',
          confirmButtonText: '확인'
        });
        return res;
      }

    });


  },

  // 명의이전 서류 첨부
  nameChangeFileUpload: async (auctionId, file) => {

    console.log('file', file);
    console.log('auctionId', auctionId);

    const formData = new FormData();
    formData.append('nameChange_file', file);

    await Alpine.store('api').post(`/api/auctions/${auctionId}/name-change-file-upload`, formData).then(res => {
      console.log('res', res);

      if(res.statusText == 'OK') {
        Alpine.store('swal').fire({
          title: '첨부 성공',
          text: res.message,
          icon: 'success',
          confirmButtonText: '확인'
        }).then(() => {
          window.location.reload();
        });
      }

      if(res.isError) {
        Alpine.store('swal').fire({
          title: '첨부 실패',
          text: res.message,
          icon: 'error',
          confirmButtonText: '확인'
        });
      }

      return res;
    });
  },

  checkAuctionEntryPublic: async (file) => {

    console.log('공매 엑셀 파일 확인',file);

    const formData = new FormData();
    if(file){
        formData.append('file', file);
    }

    try {
      const res = await Alpine.store('api').post(`/api/auctions/entryPublic`, formData);
      console.log('res', res);
      return res;
    } catch (error) {
      console.error('엑셀 파일 검증 오류:', error);
      return {
        isError: true,
        message: '엑셀 파일 검증 중 오류가 발생했습니다.'
      };
    }
  },

  getNiceDnrHistory: async (owner_name, car_no) => {

    try {
      const res = await Alpine.store('api').get(`/api/getNiceDnrHistory`, {
        owner: owner_name,
        no: car_no
      });
      return res;
    } catch (error) {
      console.error('NICE D&R 이력 조회 오류:', error);
      return {
        isError: true,
        message: 'NICE D&R 이력 조회 중 오류가 발생했습니다.'
      };
    }
  },

  getCarHistoryCrash: async (carNumber) => {
    try {
      const res = await Alpine.store('api').get(`/api/carHistoryCrash`, {
        car_no: carNumber
      });
      return res;
    } catch (error) {
      console.error('차량 사고 이력 조회 오류:', error);
      return {
        isError: true,
        message: '차량 사고 이력 조회 중 오류가 발생했습니다.'
      };
    }
  }

};  
