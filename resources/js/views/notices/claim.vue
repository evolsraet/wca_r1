<template>
    <div class="container">
      <div class="main-container py-5 container">
        <h4 class="mb-4">클레임 현황</h4>
          <div class="search-type">
                        <input type="text" class="border-6" placeholder="모델명,차량번호,지역">
                        <button type="button" class="search-btn">검색</button>
                    </div>
                </div>
                <div class="container mb-3">
                    <div class="registration-content">
                        <div class="text-start status-selector">
                        <input type="radio" name="status" value="all" id="all" hidden checked @change="setFilter('all')">
                        <label for="all" class="mx-2">전체</label>

                        <input type="radio" name="status" value="ing" id="ongoing" hidden @change="setFilter('ing')">
                        <label for="ongoing">진행중</label>

                        <input type="radio" name="status" value="done" id="completed" hidden @change="setFilter('done')">
                        <label for="completed" class="mx-2">완료</label>
                        </div>
                        </div>
                        <!-- board seach area -->
                        <div class="o_table_mobile my-5">
                          <div class="tbl_basic tbl_dealer">
                              <div class="select-dealer">
                                <table>
                                    <tbody>
                                        <tr>
                                            <th>No.</th>
                                            <th>등록일</th>
                                            <th>매물번호</th>
                                            <th>상태</th>
                                            <th>관리</th>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>24-03-15</td>
                                            <td><p class="blue-box-ty03">4751982</p></td>
                                            <td>접수</td>
                                            <td class="d-flex ms-2 justify-content-center btn-apply" @click.prevent="openAlarmModal">상세</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <AlarmModal ref="alarmModal" />
                              </div>
                          </div>
                      </div>
                </div>  
              </div>
            <Footer/>
  </template>
  
  <script setup>
  import { useStore } from "vuex";
  import { computed , ref,inject } from "vue";
  import { useRouter } from 'vue-router';
  import Swal from 'sweetalert2';
  import AlarmModal from '@/views/modal/AlarmModal.vue';
  import Footer from "@/views/layout/footer.vue"
  import { cmmn } from '@/hooks/cmmn';
  import carInfo from '../../../../resources/img/electric-car.png';
  const swal = inject('$swal');
  const { wica } = cmmn();
  const router = useRouter();
  const store = useStore();
  const user = computed(() => store.getters["auth/user"]);
  const alarmModal = ref(null);
  const isDealer = computed(() => user.value?.roles?.includes('dealer'));
  const isUser = computed(() => user.value?.roles?.includes('user'));

  const openAlarmModal = () => {
   /* const text= `<div class="enroll_box" style="position: relative;">
                  <img src="${carInfo}" alt="자동차 이미지" width="160" height="160">
                  <p class="overlay_text04">해당 서비스는 개발 중 상태입니다.</p>
               </div>`;
  wica.ntcn(swal)
    .useHtmlText() // HTML 태그 인 경우 활성화
    .addClassNm('primary-check') // 클래스명 변경, 기본 클래스명: wica-salert
    .addOption({ padding: 20, height: 265 }) // swal 기타 옵션 추가
    .callback(function (result) {
      if (result.isOk) {
        // 확인 버튼을 눌렀을 때의 동작
        reAuction();
      } else if (!result.isOk) {
        // 취소 버튼을 눌렀을 때의 동작
        cancelAuction();
      } else {
        console.error('Unexpected result:', result);
      }
    })
    .confirm(text);*/

  /*if (alarmModal.value) {
    alarmModal.value.openModal();
  }*/
};
  </script>
