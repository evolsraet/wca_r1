<template>
<h4><span class="admin-icon admin-icon-menu"></span>입금 관리</h4>
  <div class="row justify-content-center my-5">
  <div class="container-fluid" v-if="auctionDetails">
    <form @submit.prevent="registerAuction">
      <div>
        <div class="container mov-wide my-4">
          <div>
            <div class="mb-4">
              <div>
                <div class="d-flex align-items-baseline justify-content-between">
                  <h4>No.3801<span class="tc-light-gray ms-3 fw-lighter">차량번호 {{auction.car_no}}</span></h4>
                  <a class="btn-apply" @click="detailAuction()">자세히 보기</a>
                </div>
                <div class="sell-info mb-5">
                    <div class="car-image-style">
                      <div class="card-img-top-ty01"></div>
                    </div>
                    <div class="car-info">
                        <div class="item">
                            <span class="label">성공수수료</span>
                            <span class="value">{{ auction.success_fee_label }}</span>
                        </div>
                        <div class="item">
                            <span class="label">진단 비</span>
                            <span class="value">{{auction.diag_fee_label}}</span>
                        </div>
                        <div class="total">
                            <span>합 계</span>
                            <span>{{ add_pee }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p class="tc-light-gray">입금 상태</p>
                    <select class="form-select" :v-model="auction.status" @change="changeStatus($event)" id="status">
                    <option value="done">입금 완료</option>
                    <option value="dlvr">입금 대기</option> <!-- dlvr자리 -->
                    </select>
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">낙찰 금액</p>
                  <input v-model="auction.final_price" class="input-dis form-control bg-secondary bg-opacity-10" readonly/>
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">입금 총액</p>
                  <input value="35,000,000 원" class="input-dis form-control bg-secondary bg-opacity-10" readonly/>
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">미수금</p>
                  <input value="5,000,000 원"  class="input-dis tc-red form-control bg-secondary bg-opacity-10" readonly/>
                </div>
                
                <!--       <div class="container-receipt mt-5">
                      <img src="../../../../img/admin/admin-back-sell.png" alt="Image" style="width:100%; height: auto;">
                      <div class="text-block">
                        <h4>Your Text Here</h4>
                        <p>Additional Text Here</p>
                      </div>
                    </div>
                -->
            
                <div class="btn-group mt-4">
                  <button class="btn btn-primary tc-wh"> 저장 </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  </div>
</template>
<script setup>
import { createApp,h,ref, onMounted, reactive, onUnmounted, inject  } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import useAuctions from '@/composables/auctions';
import { cmmn } from '@/hooks/cmmn';
import LawGid from '@/views/modal/deposit/detailAuction.vue';

const router = useRouter();
const route = useRoute();
const { getAuctionById, updateAuctionStatus, AuctionCarInfo } = useAuctions();
const { amtComma, formatCurrency, wica } = cmmn();
const auctionDetails = ref(null);

const swal = inject('$swal');
const auction = reactive({ 
    car_no: '',
    owner_name: '',
    bank: '',
    account: '',
    memo: '',
    addr_post: '',
    status : '',
    addr1: '',
    addr2: '',
    final_at: '',
    choice_at: '',
    done_at: '',
    success_fee: '0',
    diag_fee: '0',
    total_fee: '0 원',
    hope_price: '0',
    final_price: '0 원',
    success_fee_label: '0 원',
    diag_fee_label: '0 원',
}); 
const add_pee = ref(0);

function changeStatus(event) {
  auction.status = event.target.value;
}

const fetchAuctionDetails = async () => {
  try {
    const id = route.params.id;
    const data = await getAuctionById(id);
    auctionDetails.value = data;
  } catch (error) {
    console.error('Error fetching auction details:', error);
  }
};

async function registerAuction(){
  try {
    const id = route.params.id;
    
    wica.ntcn(swal)
    .title('수정하시겠습니까?') // 알림 제목
    .icon('Q') //E:error , W:warning , I:info , Q:question
    .callback(async function(result) {
        if(result.isOk){
          const updateResult = await updateAuctionStatus(id, 'done');
          if(updateResult.isSuccess){
            wica.ntcn(swal)
            .icon('I') //E:error , W:warning , I:info , Q:question
            .callback(function(result) {
                if (result.isOk) {
                  router.push({ name: 'deposit.index' }); 
                }
            })
            .alert('입금상태가 정상적으로 수정되었습니다.');
          }else{
            validationErrors.value = result.msg;
            wica.ntcn(swal)
            .title('변경 실패')
            .icon('E') //E:error , W:warning , I:info , Q:question
            .alert('입금상태 변경에 실패하였습니다.');
          }
        }
    }).confirm();

   
  } catch (error) {
    console.error('Error updating auction status:', error);
  }
};

const detailAuction = async () => {
  const container = document.createElement('div');
  
  const carInfoForm = {
    owner: auction.owner_name,
    no: auction.car_no,
    forceRefresh: ""
  };
  const carDetail = ref({});
  const carInfoResponse = await AuctionCarInfo(carInfoForm);
  const carData = carInfoResponse.data;

  carDetail.no = carData.no;
  carDetail.model = carData.model;
  carDetail.modelSub = carData.modelSub;
  carDetail.grade = carData.grade;
  carDetail.gradeSub = carData.gradeSub;
  carDetail.year = carData.year;
  carDetail.fuel = carData.fuel;
  carDetail.mission = carData.mission;

  const app = createApp({
    render() {
      return h(LawGid, { content: carDetail });
    }
  });
  
  app.mount(container);
  
  const text = container.innerHTML;

  wica.ntcn(swal)
    .useHtmlText() // HTML 태그 인 경우 활성화
    .addOption({ padding: 20 }) // swal 기타 옵션 추가
    .addClassNm('intro-modal')
    .useClose()
    .callback(function (result) {
      // 추가적인 콜백 함수 내용이 필요하다면 여기에 작성
    })
    .confirm(text);

  app.unmount(); // Unmount the app after getting the HTML content
};


onMounted(async () => {
  await fetchAuctionDetails();
  
  auction.car_no = auctionDetails.value.data.car_no;
  auction.owner_name = auctionDetails.value.data.owner_name;
  document.getElementById("status").value = auctionDetails.value.data.status;
  auction.bank = auctionDetails.value.data.bank;
  auction.account = auctionDetails.value.data.account;
  auction.memo = auctionDetails.value.data.memo;
  auction.addr_post = auctionDetails.value.data.addr_post;
  auction.addr1 = auctionDetails.value.data.addr1;
  auction.addr2 = auctionDetails.value.data.addr2;
  auction.success_fee = auctionDetails.value.data.success_fee;
  auction.diag_fee =auctionDetails.value.data.diag_fee;

  add_pee.value = formatCurrency(auction.success_fee + auction.diag_fee);
  if(auctionDetails.value.data.success_fee){
    auction.success_fee_label = formatCurrency(auctionDetails.value.data.success_fee);
  }
  if(auctionDetails.value.data.diag_fee){
    auction.diag_fee_label = formatCurrency(auctionDetails.value.data.diag_fee);
  }
  if(auctionDetails.value.data.final_price){
    auction.final_price = amtComma(auctionDetails.value.data.final_price);
  }
  
});

</script>



<style scoped>
body, html {
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #fff;
}

.receipt {
    width: 80%;
    max-width: 600px;
    height: 90%;
    border: 1px solid #000;
    position: relative;
    background: #fff;
    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.perforated-top {
    width: 100%;
    height: 20px;
    position: absolute;
    top: -10px;
    left: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 5px;
}

.perforated-top span {
    width: 20px;
    height: 20px;
    background: #fff;
    border: 1px solid #000;
    border-radius: 50%;
    box-shadow: 0 0 0 10px #fff;
    margin-left: -10px; /* To create overlapping effect */
}




.my-auction{
  background-color: #f7f8fb !important;
}
.bottom-sheet {
  height: auto !important;
}
.card-img-top-ty01 {
  width: 100%;
  height: 160px;
  background-image: url('../../../../img/car_example.png');
  background-size: cover;
  background-position: center;
  border-radius: 6px;
}
.slide-enter-active, .slide-leave-active {
  transition: all 0.5s ease;
}
.slide-enter-from, .slide-leave-to {
  max-height: 0;
  opacity: 0;
  overflow: hidden;
}
.slide-enter-to, .slide-leave-from {
  max-height: 1000px;
  opacity: 1;
  overflow: hidden;
}

   .sell-info {
          display: flex;
          align-items: center;
          border: 1px solid #ccc;
          border-radius: 10px;
          width: 100%;
          padding-right: 25px;
      }
      .car-image-style {
          flex: 1;
      }
      .car-info {
          flex: 1;
          padding-left: 25px;
      }
      .car-info .item {
          display: flex;
          justify-content: space-between;
          margin-bottom: 10px;
      }
      .car-info .item .label {
          font-weight: bold;
      }
      .car-info .total {
          display: flex;
          justify-content: space-between;
          font-size: 1.2em;
          font-weight: bold;
          color: blue;
      }
  .container-receipt {
    position: relative;
    text-align: center;
    color: white;
  }
  .text-block {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: rgba(0, 0, 0, 0.5);
    padding: 20px;
  }
 @media (max-width: 400px) {
.car-info {
  padding-left: 10px;
}
.sell-info{
  padding-right: 15px;
}
}
</style>
