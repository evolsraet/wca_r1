<template>
    <div class="container">

      <div class="main-container py-5 text-center container" v-if="isAdmin">
        <section class="notice">
            <div class="page-title">
                  <div class="container">
                      <h3>관리자 클레임</h3>
                  </div>
              </div>
              <div id="board-search">
                  <div class="container">
                      <div class="search-window">
                          <form action="">
                              <div class="search-wrap">
                                  <label for="search" class="blind">클레임 내용 검색</label>
                                  <input id="search" type="search" name="" placeholder="검색어를 입력해주세요." value="">
                                  <button type="submit" class="btn btn-dark">검색</button>
                              </div>
                          </form>
                      </div>
                  </div>
              </div>
            
              <div id="board-list">
                  <div class="container">
                      <table class="board-table">
                          <thead>
                          <tr>
                              <th scope="col" class="th-num">번호</th>
                              <th scope="col" class="th-title">제목</th>
                              <th scope="col" class="th-date">등록일</th>
                          </tr>
                          </thead>
                          <tbody>
                          <tr>
                              <td>3</td>
                              <th><a href="#!">[클레임] 개인정보 처리방침 변경안내처리방침</a></th>
                              <td>2017.07.13</td>
                          </tr>

                          <tr>
                              <td>2</td>
                              <th><a href="#!">클레임 안내입니다. 이용해주셔서 감사합니다</a></th>
                              <td>2017.06.15</td>
                          </tr>

                          <tr>
                              <td>1</td>
                              <th><a href="#!">클레임 안내입니다. 이용해주셔서 감사합니다</a></th>
                              <td>2017.06.15</td>
                          </tr>
                          </tbody>
                      </table>
                  </div>
              </div>

          </section>
      </div>
      <div class="main-container py-5 text-center container"v-else-if="isDealer">
          <div class="search-type">
                        <input type="text" placeholder="모델명,차량번호,지역">
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
                        <div class="overflow-auto select-dealer">
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
  import { computed , ref } from "vue";
  import { useRouter } from 'vue-router';
  import AlarmModal from '@/views/modal/AlarmModal.vue';
  import Footer from "@/views/layout/footer.vue"
  
  const router = useRouter();
  const store = useStore();
  const user = computed(() => store.getters["auth/user"]);
  const alarmModal = ref(null);
  const isDealer = computed(() => user.value?.roles?.includes('dealer'));
  const isUser = computed(() => user.value?.roles?.includes('user'));


  const openAlarmModal = () => {
console.log("openAlarmModal called");
if (alarmModal.value) {
  alarmModal.value.openModal();
}
};
  </script>

<style scoped>
table {
  border-collapse: collapse;
  border-spacing: 0;
}
section.notice {
  padding: 80px 0;
}

.page-title {
  margin-bottom: 60px;
}
.page-title h3 {
  font-size: 28px;
  color: #333333;
  font-weight: 400;
  text-align: center;
}

#board-search .search-window {
  padding: 15px 0;
  background-color: #f9f7f9;
}
#board-search .search-window .search-wrap {
  position: relative;
  padding-right: 124px;
  margin: 0 auto;
  width: 80%;
  max-width: 564px;
}
#board-search .search-window .search-wrap input {
  height: 40px;
  width: 100%;
  font-size: 14px;
  padding: 7px 14px;
  border: 1px solid #ccc;
}
#board-search .search-window .search-wrap input:focus {
  border-color: #333;
  outline: 0;
  border-width: 1px;
}
#board-search .search-window .search-wrap .btn {
  position: absolute;
  right: 0;
  top: 0;
  bottom: 0;
  width: 108px;
  padding: 0;
  font-size: 16px;
}

.board-table {
  font-size: 13px;
  width: 100%;
  border-top: 1px solid #ccc;
  border-bottom: 1px solid #ccc;
}

.board-table a {
  color: #333;
  display: inline-block;
  line-height: 1.4;
  word-break: break-all;
  vertical-align: middle;
}
.board-table a:hover {
  text-decoration: underline;
}
.board-table th {
  text-align: center;
}

.board-table .th-num {
  width: 100px;
  text-align: center;
}

.board-table .th-date {
  width: 200px;
}

.board-table th, .board-table td {
  padding: 14px 0;
}
.table-container {
  overflow-x: auto; 
  width: 100%; 
}
.board-table tbody td {
  border-top: 1px solid #e7e7e7;
  text-align: center;
}

.board-table tbody th {
  padding-left: 28px;
  padding-right: 14px;
  border-top: 1px solid #e7e7e7;
  text-align: left;
}

.btn {
  display: inline-block;
  padding: 0 30px;
  font-size: 15px;
  font-weight: 400;
  background: transparent;
  text-align: center;
  white-space: nowrap;
  vertical-align: middle;
  -ms-touch-action: manipulation;
  touch-action: manipulation;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  border: 1px solid transparent;
  text-transform: uppercase;
  -webkit-border-radius: 0;
  -moz-border-radius: 0;
  border-radius: 0;
  -webkit-transition: all 0.3s;
  -moz-transition: all 0.3s;
  -ms-transition: all 0.3s;
  -o-transition: all 0.3s;
  transition: all 0.3s;
}

.btn-dark {
  background: #555;
  color: #fff;
}

.btn-dark:hover, .btn-dark:focus {
  background: #373737;
  border-color: #373737;
  color: #fff;
}

.btn-dark {
  background: #555;
  color: #fff;
}

.btn-dark:hover, .btn-dark:focus {
  background: #373737;
  border-color: #373737;
  color: #fff;
}

/* reset */

* {
  list-style: none;
  text-decoration: none;
  padding: 0;
  margin: 0;
  box-sizing: border-box;
}
.clearfix:after {
  content: '';
  display: block;
  clear: both;
}
.container {
  width: 1100px;
  margin: 0 auto;
}
.blind {
  position: absolute;
  overflow: hidden;
  clip: rect(0 0 0 0);
  margin: -1px;
  width: 1px;
  height: 1px;
}
@media (max-width: 768px) {
  .container {
    width: auto; 
    padding: 0 20px; 
  }

  #board-search .search-window .search-wrap {
    padding-right: 15px; 
  }

  #board-search .search-window .search-wrap .btn {
    width: 60px; 
  }

  .board-table .th-num, .board-table .th-date {
    display: none; 
  }

  .board-table th, .board-table td {
    padding: 10px 5px;
  }

  .page-title h3 {
    font-size: 20px; 
  }

  .btn {
    padding: 0 15px; 
  }

  .table-container {
    overflow-x: auto;
  }
}

@media (max-width: 400px) {
  .table-container {
    padding: 0 10px; /* 추가 패딩을 줄여 테이블이 더 많은 공간을 사용할 수 있게 합니다. */
  }

  .board-table {
    min-width: 600px; /* 테이블의 최소 너비를 설정하여 컨텐츠가 잘림 없이 표시됩니다. */
  }

  .board-table th, .board-table td {
    padding: 5px; /* 셀의 패딩을 줄여 정보 밀도를 높입니다. */
  }
}

</style>