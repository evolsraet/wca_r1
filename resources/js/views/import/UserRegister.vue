<template>
      <form @submit.prevent="handleSubmitBtn">
        <div v-if="userEditURL" class="form-group profile-content">
          <div class="img-form">
          <div class="edit-photo-container" @click="triggerFileInput">
            <img :src="photoUrl" alt="Profile Photo" class="profile-photo" />
            <input type="file" ref="fileInput" @change="onFileChange" id="photo" class="d-none" />
          </div>
            <div v-if="photoUrl !== profileDom" class="icon-trash-img" @click="deletePhotoImg"></div>
          </div>
          <!--<p class="text-secondary opacity-50">사진은 140 X 140 를 권장합니다.</p>-->
        </div>
        <div v-if="userEditURL" class="my-4">
          <h5 class="text-secondary opacity-50">&#8251<span class="mx-2">정보 수정 시 심사를 받으실 수 있습니다.</span></h5>
        </div>
        <div v-if="userEditURL || adminEditURL" class="form-group">
          <label for="name">가입일자</label>
          <input type="text" v-model="user.created_at" id="createdAt" class="input-dis form-control" readonly/>
        </div>
        <div v-if="adminEditURL" class="form-group">
          <label for="name">최종 수정일</label>
          <input type="text" v-model="user.updated_at" id="updated_at" class="input-dis form-control" readonly/>
        </div>
        <div class="form-group">
          <label for="name"><span class="text-danger">*</span>이름</label>
          <input type="text" v-model="profile.name" id="name" class="form-control" placeholder="이름"/>
          <div v-if="registerURL || adminCreateURL || adminEditURL" class="text-danger mt-1">
              <div v-for="message in validationErrors?.name">
                  {{ message }}
              </div>
          </div>
        </div>
        <div v-if="userEditURL || adminEditURL" class="form-group">
          <label for="name"><span class="text-danger">*</span>전화번호</label>
          <input type="text" id="phone" v-model="profile.phone" class="input-dis form-control" readonly/>
        </div>
        <div v-if="registerURL || adminCreateURL" class="form-group">
          <label for="name"><span class="text-danger">*</span>전화번호</label>
          <input type="text" id="phone" v-model="profile.phone" class="form-control" placeholder="- 없이 전화번호를 입력해 주세요"/>
          <div v-if="registerURL || adminCreateURL || adminEditURL" class="text-danger mt-1">
            <div v-for="message in validationErrors?.phone">
                {{ message }}
            </div>
          </div>
        </div>
        <div v-if="registerURL || adminCreateURL" class="form-group">
          <label for="email">이메일</label>
          <input type="text" v-model="profile.email" id="email" class="form-control" placeholder="example@demo.com"/>
          <div v-if="registerURL || adminCreateURL" class="text-danger mt-1">
              <div v-for="message in validationErrors?.email">
                  {{ message }}
              </div>
          </div>
        </div>
        <div v-if="userEditURL || adminEditURL" class="form-group">
          <label for="email">이메일</label>
          <input type="text" v-model="profile.email" id="email" class="input-dis form-control" readonly/>
        </div>
        <div v-if="registerURL || adminCreateURL" class="form-group">
          <label for="email"><span class="text-danger">*</span>비밀번호</label>
          <input autocomplete="one-time-code" type="password" v-model="profile.password" id="password" class="form-control" placeholder="6~8자리 숫자,영어,특수문자 혼합"/>
          <div v-if="registerURL || adminCreateURL" class="text-danger mt-1">
              <div v-for="message in validationErrors?.password">
                  {{ message }}
              </div>
          </div>
        </div>
        <div v-if="userEditURL" class="form-group">
          <label for="email">변경 비밀번호</label>
          <input autocomplete="one-time-code" type="password" v-model="profile.password" id="password" class="form-control" placeholder="6~8자리 숫자,영어,특수문자 혼합"/>
          <div v-if="userEditURL" class="text-danger mt-1">
              <div v-for="message in validationErrors?.password">
                  {{ message }}
              </div>
          </div>
        </div>
        <div v-if="userEditURL || registerURL || adminCreateURL" class="form-group">
          <label for="email"><span class="text-danger" v-if="adminCreateURL || registerURL">*</span>비밀번호 확인</label>
          <input autocomplete="one-time-code" type="password" v-model="profile.password_confirmation" id="password_confirmation" class="form-control" placeholder="비밀번호를 다시 입력해주세요"/>
          <div v-if="registerURL || adminCreateURL || userEditURL" class="text-danger mt-1">
              <div v-for="message in validationErrors?.password_confirmation">
                  {{ message }}
              </div>
          </div>
        </div>
        <div v-if="adminEditURL" class="mb-3">
            <label for="email" class="form-label">승인여부</label>
            <select class="form-select" :v-model="profile.status" @change="changeStatus($event)" id="status">
                <option v-for="(label, value) in statusLabel" :key="value" :value="value" :selected="value == profile.status">{{ label }}</option>
            </select>
        </div>
        <div v-if="adminEditURL" class="mb-3">
            <label for="user-title" class="form-label"
                >사진(본인 확인용)</label
            >
            <div class="file-upload-container">
                <img :src="photoUrl" alt="Profile Photo" class="profile-photo" v-if="profile.file_user_photo_name"/>
            </div>
            <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadPhoto">
                파일 첨부
            </button>
            <input type="file" @change="handleFileUploadPhoto" ref="fileInputPhoto" style="display:none" id="file_user_photo">
            <div class="text-start text-secondary opacity-50" v-if="profile.file_user_photo_name">
                사진 파일 : <a :href=photoUrl download>{{ profile.file_user_photo_name }}</a>
            </div>
        </div>
        <div v-if="adminEditURL || adminCreateURL" class="mb-3">
            <label for="role" class="form-label">회원유형</label>
            <select class="form-select" :v-model="profile.role" @change="changeRoles($event)" id="role">
                <option value="user" :selected="profile.role == 'user'">일반</option>
                <option value="dealer" :selected="profile.role == 'dealer'">딜러</option>
            </select>
        </div>
        <div class="text-center" v-if="registerURL">
          <div class="form-group dealer-check">
              <label for="dealer">혹시 딜러이신가요? </label>
              <div class="check_box">
                  <input type="checkbox" id="ch2" v-model="profile.isDealer" class="form-control">
                  <label for="ch2">네,딜러에요!</label>
              </div>
          </div>
          <p class="text-secondary opacity-50">딜러라면 추가 정보 입력이 필요해요</p>
          <a @click="openModal('privacy')" class="icon-link mt-5 mb-3">
              <img src="../../../img/Icon-file.png" class="ms-2" alt="회원약관 및 개인정보 처리방침">위카모빌리티 회원약관 및 개인정보처리 방침
          </a>
        </div>
        <div v-if="isDealer || profile.isDealer">
          <div v-if="registerURL || adminCreateURL" class="mb-3">
                <label for="user-title" class="form-label"
                    >사진 (본인 확인용)</label
                >
                <div class="file-upload-container">
                    <img :src="photoUrl" alt="Profile Photo" class="profile-photo" v-if="profile.file_user_photo_name"/>
                </div>
                <input type="file" @change="handleFileUploadPhoto" ref="fileInputPhoto" style="display:none">
                <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadPhoto">
                    파일 첨부
                </button>
                <div class="text-start text-secondary opacity-50" v-if="profile.file_user_photo_name">
                  사진 (본인 확인용) 파일 : <a :href=photoUrl download>{{ profile.file_user_photo_name }}</a>
                </div>
                <div v-for="message in validationErrors?.file_user_photo_name" class="text-danger mt-1">
                  {{ message }}
                </div>
          </div>
          <div class="form-group">
            <label for="dealerName"><span class="text-danger">*</span>딜러 이름</label>
            <input type="text" v-model="profile.dealer_name" id="dealerName" class="form-control" placeholder="이름"/>
            <div v-for="message in validationErrors?.name" class="text-danger mt-1">
                  {{ message }}
            </div>
          </div>
          <div v-if="registerURL || adminCreateURL || userEditURL || adminEditURL" class="form-group">
            <label for="name"><span class="text-danger">*</span>연락처</label>
            <input type="text" id="phone" v-model="profile.dealerContact" class="form-control" placeholder="- 없이 전화번호를 입력해 주세요"/>
            <div v-for="message in validationErrors?.phone" class="text-danger mt-1">
                  {{ message }}
            </div>
          </div>
          <div v-if="registerURL || adminCreateURL || userEditURL || adminEditURL" class="form-group">
              <label for="dealerBirthDate"><span class="text-danger">*</span>생년월일</label>
              <input type="date" id="dealerBirthDate" v-model="profile.dealerBirthDate" placeholder="1990-12-30">
              <div v-for="message in validationErrors?.birthday" class="text-danger mt-1">
                  {{ message }}
            </div>
          </div>
          <div class="form-group">
            <label for="dealer"><span class="text-danger">*</span>소속상사</label>
            <input type="text" v-model="profile.company" class="form-control" placeholder="상사명(상사 정식 명칭)"/>
            <div v-for="message in validationErrors?.company" class="text-danger mt-1">
                  {{ message }}
            </div>
          </div>
          <div class="form-group">
            <label for="dealer"><span class="text-danger">*</span>소속상사 직책</label>
            <input type="text" v-model="profile.dealerCompanyDuty" class="form-control" placeholder="사원"/>
            <div v-for="message in validationErrors?.company_duty" class="text-danger mt-1">
                  {{ message }}
            </div>
          </div>
          <div class="form-group">
            <label for="dealer"><span class="text-danger">*</span>소속상사 주소 </label>
            <input type="text" @click="editPostCode('daumPostcodeInput')" v-model="profile.company_post" class="input-dis form-control" placeholder="우편번호" readonly/>
            <button type="button" class="search-btn" @click="editPostCode('daumPostcodeInput')">검색</button>
            <div id="daumPostcodeInput" style="display: none; border: 1px solid; width: 100%; height: 466px; margin: 5px 0px; position: relative">
                <img src="//t1.daumcdn.net/postcode/resource/images/close.png" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" @click="closePostcode('daumPostcodeInput')">
            </div>
          <div class="input-with-button">
            <input type="text" @click="editPostCode('daumPostcodeInput')" v-model="profile.company_addr1" class="input-dis form-control" placeholder="주소" readonly/>
            
          </div>
            <input type="text" v-model="profile.company_addr2" class="form-control" placeholder="상세주소"/>
            <div v-for="message in validationErrors?.company_post" class="text-danger mt-1">
                  {{ message }}
            </div>
            <div v-for="message in validationErrors?.company_addr1" class="text-danger mt-1">
                  {{ message }}
            </div>
            <div v-for="message in validationErrors?.company_addr2" class="text-danger mt-1">
                  {{ message }}
            </div>
          </div>
          <div class="form-group">
            <label for="dealer">인수차량 도착지 주소</label>
            <input type="text" v-model="profile.receive_post" class="input-dis form-control" placeholder="우편번호" readonly />
            <button type="button" class="search-btn" @click="editPostCodeReceive('daumPostcodeDealerReceiveInput')">검색</button>
            <div id="daumPostcodeDealerReceiveInput" style="display: none; border: 1px solid; width: 100%; height: 466px; margin: 5px 0px; position: relative">
              <img src="//t1.daumcdn.net/postcode/resource/images/close.png" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" @click="closePostcode('daumPostcodeDealerReceiveInput')">
            </div>
            <div class="input-with-button">
              <input type="text" v-model="profile.receive_addr1" class="input-dis form-control" placeholder="주소" readonly />
            </div>
            <input type="text" v-model="profile.receive_addr2" class="form-control" placeholder="상세주소"/>
          </div>
          <div class="form-group">
            <label for="dealer"><span class="text-danger">*</span>소개</label>
            <textarea type="text" v-model="profile.introduce" class="custom-textarea mt-2" placeholder="소개를 입력해주세요."></textarea>
            <div v-for="message in validationErrors?.introduce" class="text-danger mt-1">
                  {{ message }}
            </div>
          </div>

          <div class="form-group">
            <div v-if="registerURL">
              <p>추가 서류가 필요해요</p>
              <li>사업자등록증</li>
              <li>매도용인감정보</li>
              <li>매매업체 대표증 or 종사원증</li>
              <div class="d-flex justify-content-flex-end">
                <a href="#" class="seal-info text-secondary opacity-50 my-2 float-end" alt="인감정보양식 다운로드 링크">인감정보양식 다운로드</a>
              </div>
            </div>
            <div class="mb-3">
                <label for="user-title" class="form-label"
                    >사업자 등록증</label
                >
                <input type="file" @change="handleFileUploadBiz" ref="fileInputRefBiz" style="display:none">
                <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadBiz">
                    파일 첨부
                </button>
                <div class="text-start text-secondary opacity-50" v-if="profile.file_user_biz_name">
                    사업자 등록증 파일 : <a :href="fileBizUrl" download>{{ profile.file_user_biz_name }}</a>
                </div>
                <div class="form-check mt-2" v-if="adminEditURL">
                  <input type="checkbox" 
                         class="form-check-input" 
                         id="bizCheck" 
                         :checked="profile.biz_check == 1"
                         @change="profile.biz_check = $event.target.checked ? 1 : 0">
                  <label class="form-check-label" for="bizCheck">사업자 정보 확인 완료 (체크 하면 매물정보에서 딜러의 사업자정보를 볼 수 있도록 활성화 합니다.)</label>
                </div>
                <div v-for="message in validationErrors?.file_user_biz_name" class="text-danger mt-1">
                  {{ message }}
                </div>
            </div>
            <div class="mb-3">
                <label for="user-title" class="form-label"
                    >매도용인감정보</label
                >
                <input type="file" @change="handleFileUploadSign" ref="fileInputRefSign" style="display:none">
                <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadSign">
                    파일 첨부
                </button>
                <div class="text-start text-secondary opacity-50" v-if="profile.file_user_sign_name">
                    매도용인감정보 파일 : <a :href=fileSignUrl download>{{ profile.file_user_sign_name }}</a>
                </div>
                <div v-for="message in validationErrors?.file_user_sign_name" class="text-danger mt-1">
                  {{ message }}
                </div>
            </div>
            <div class="mb-3">
                <label for="user-title" class="form-label"
                    >매매업체 대표증 or 종사원증</label
                >
                <input type="file" @change="handleFileUploadCert" ref="fileInputRefCert" style="display:none">
                <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadCert">
                    파일 첨부
                </button>
                <div class="text-start text-secondary opacity-50" v-if="profile.file_user_cert_name">
                    매매업체 대표증 / 종사원증 파일 : <a :href=fileCertUrl download>{{ profile.file_user_cert_name }}</a>
                </div>
                <div v-for="message in validationErrors?.file_user_cert_name" class="text-danger mt-1">
                  {{ message }}
                </div>
            </div>
          </div>
        </div>

        <!-- HTML 부분 - 패널티 선택 드롭다운 -->
        <div v-if="adminEditURL" class="mb-3">
            <label for="penalty" class="form-label">패널티 설정</label>
            <select class="form-select" :v-model="profile.penalty" @change="changePenalty($event)" id="penalty">
                <option value="">패널티 없음</option>
                <option value="warning1">경고1</option>
                <option value="warning2">경고2</option>
                <option value="expulsion">제명</option>
            </select>
            <div class="text-secondary opacity-50 mt-2">
              - 경고1: 3일<br>
              - 경고2: 30일<br>
              - 제명: 영구적 (종료 시간 없음)
            </div>
        </div>

        <div v-if="userEditURL">
          <button type="submit" class="mt-3 w-100 btn btn-primary">저장</button>
        </div>
        <div v-if="registerURL">
          <button type="submit" class="mt-3 w-100 btn btn-primary">약관 동의 및 회원가입</button>
        </div>
        <div v-if="adminEditURL" class="mt-4">
          <button class="w-100 btn btn-primary">
            <span>저장</span>
          </button>
        </div>
        <div v-if="adminCreateURL" class="mt-4">
          <button class="w-100 btn btn-primary">
            <span>등록</span>
          </button>
        </div>

        <div v-if="adminEditURL" class="mt-4">
          <button type="button" class="w-100 btn btn-success" @click="approveUser">
            <span>심사승인</span>
          </button>          
        </div>

      </form>
      <transition name="fade" mode="out-in">
        <LawGid v-if="isModalOpen" :content="modalContent" @close="closeModal"/>
      </transition>
  </template>
  
 
  <script setup>
  import { ref, onMounted ,computed , inject,createApp ,h } from 'vue';
  import { useStore } from 'vuex';
  import { useRoute } from 'vue-router';
  import profileDom from '/resources/img/profile_dom.png'; 
  import { cmmn } from '@/hooks/cmmn';
  import useUsers from "@/composables/users";
  import LawGid from '@/views/modal/LawGid.vue';
  const closeModal = () => {
    isModalOpen.value = false;
  };
  const { getUser , user, setRegisterUser, updateProfile, updateUser,adminStoreUser, validationErrors } = useUsers();
  const route = useRoute();
  const isModalOpen = ref(false);
  const photoUrl = ref(profileDom);
  const fileSignUrl = ref('');
  const fileCertUrl = ref('');
  const fileBizUrl = ref('');

  const fileInputPhoto = ref(null);
  const fileInputRefBiz = ref(null);
  const fileInputRefSign = ref(null);
  const fileInputRefCert = ref(null);
  
  const userEditURL = ref(false);
  const registerURL = ref(false);
  const adminEditURL = ref(false);
  const adminCreateURL = ref(false);

  const store = useStore();
  const swal = inject('$swal');

  const profile = ref({
    name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation : '',
    dealer_name: '',
    dealerBirthDate:'',
    dealerContact: '',
    company: '',
    dealerCompanyDuty:'',
    company_post : '',
    company_addr1 : '',
    company_addr2 : '',
    receive_post : '',
    receive_addr1 : '',
    receive_addr2 : '',
    introduce : '',
    isDealer : '',
    file_user_photo:"",
    file_user_photo_name:"",
    file_user_biz:"",
    file_user_biz_name:"",
    file_user_sign:"",
    file_user_sign_name:"",
    file_user_cert:"",
    file_user_cert_name:"",
    status:'',
    role:'',
    biz_check: '0',
    //디비외
    photoImgChg : false,
    photoUUID : '',
  });
  const userId = ref(null);
  const isDealer = ref(false);
  const { openPostcode , closePostcode , wica , wicac, wicas } = cmmn();
  let statusLabel;
  
  const triggerFileInput = () => {
    fileInput.value.click();
  };

  const penaltyLabel = {
      '': '패널티 없음',
      'warning1': '경고1',
      'warning2': '경고2',
      'expulsion': '제명'
  };

  const isPenalty = ref(false);

  const changePenalty = (event) => {

    const selectedPenalty = event.target.value;
    profile.penalty = selectedPenalty;
    
    // 패널티 선택 시 승인 여부도 함께 변경
    if (selectedPenalty) {
        // 패널티가 선택되면 status도 같은 값으로 설정
        profile.status = selectedPenalty;
        
        // status 드롭다운의 값도 변경 (DOM 직접 조작)
        const statusSelect = document.getElementById('status');
        if (statusSelect) {
            statusSelect.value = selectedPenalty;
        }

        isPenalty.value = true;

    } else {
        // 패널티가 없음을 선택한 경우 status를 'ok'로 설정
        profile.status = 'ok';
        
        // status 드롭다운의 값도 변경
        const statusSelect = document.getElementById('status');
        if (statusSelect) {
            statusSelect.value = 'ok';
        }
    }

  };
  
  const fileInput = ref(null);
  const openModal = (type) => {
  const container = document.createElement('div');

  const app = createApp({
    render() {
      return h(LawGid, { content: type });
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
  const onFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      profile.value.file_user_photo = file;
      //console.log(profile.value.file_user_photo);
      const reader = new FileReader();
      reader.onload = (e) => {
        photoUrl.value = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  };
  
  function fileExstCheck(info){
      if(info.hasOwnProperty('files')){
        if(info.files.hasOwnProperty('file_user_photo')){
            if(info.files.file_user_photo[0].hasOwnProperty('original_url')){
                photoUrl.value = info.files.file_user_photo[0].original_url;
                profile.value.photoUUID = info.files.file_user_photo[0].uuid;
                profile.value.file_user_photo_name = info.files.file_user_photo[0].file_name;
            }
        }

        if(info.files.hasOwnProperty('file_user_sign')){
            if(info.files.file_user_sign[0].hasOwnProperty('original_url')){
                fileSignUrl.value = info.files.file_user_sign[0].original_url;
                profile.value.file_user_sign_name = info.files.file_user_sign[0].file_name;
            }
        }

        if(info.files.hasOwnProperty('file_user_cert')){
            if(info.files.file_user_cert[0].hasOwnProperty('original_url')){
                fileCertUrl.value = info.files.file_user_cert[0].original_url;
                profile.value.file_user_cert_name = info.files.file_user_cert[0].file_name;
            }
        }

        if(info.files.hasOwnProperty('file_user_biz')){
            if(info.files.file_user_biz[0].hasOwnProperty('original_url')){
                fileBizUrl.value = info.files.file_user_biz[0].original_url;
                profile.value.file_user_biz_name = info.files.file_user_biz[0].file_name;
            }
        }
      }
  }
  
  const setUserProfileData = async () => {
    if(userEditURL.value){
      userId.value = store.getters['auth/user'].id;
    }else if(adminEditURL.value){
      userId.value = route.params.id;
    }
    await getUser(userId.value);

    profile.value.isDealer = user.value?.roles?.includes('dealer') || false;
    profile.value.role = user.value.roles;

    if (user) {
      profile.value.name = user.value.name;
      profile.value.phone = user.value.phone;
      profile.value.email = user.value.email;
      profile.value.status = user.value.status;
      profile.value.penalty = user.value.status;
      if (profile.value.isDealer) {
        profile.value.dealer_name = user.value.dealer.name;
        profile.value.dealerContact = user.value.dealer.phone;
        profile.value.dealerBirthDate = user.value.dealer.birthday;
        profile.value.biz_check = user.value.dealer.biz_check;
        profile.value.company = user.value.dealer.company;
        profile.value.company_post = user.value.dealer.company_post;
        profile.value.company_addr1 = user.value.dealer.company_addr1;
        profile.value.company_addr2 = user.value.dealer.company_addr2;
        profile.value.receive_post = user.value.dealer.receive_post;
        profile.value.receive_addr1 = user.value.dealer.receive_addr1;
        profile.value.receive_addr2 = user.value.dealer.receive_addr2;
        profile.value.introduce = user.value.dealer.introduce;
        profile.value.dealerCompanyDuty = user.value.dealer.company_duty;
      }
      if (user.value.files) {
        fileExstCheck(user.value);
      }
    } 
    
  };
  
  function editPostCode(elementName) {
    openPostcode(elementName)
      .then(({ zonecode, address }) => {
        profile.value.company_post = zonecode;
        profile.value.company_addr1 = address;
      })
  }
  
  function editPostCodeReceive(elementName) {
      openPostcode(elementName)
      .then(({ zonecode, address }) => {
        profile.value.receive_post = zonecode;
        profile.value.receive_addr1 = address;
      })
  }
  
  onMounted(async () => {
    //await store.dispatch("auth/getUser");

    if(route.path == '/edit-profile'){
      userEditURL.value = true; 
      await setUserProfileData();
    } else if(route.path == '/register'){
      registerURL.value = true;
    } else if(route.path.includes('/admin/users/edit/')){
      statusLabel = wicas.enum(store).users();
      adminEditURL.value = true;
      await setUserProfileData();
    } else if(route.path =='/admin/users/create'){
      statusLabel = wicas.enum(store).users();
      adminCreateURL.value = true;
    }
    
  });

  function handleSubmitBtn(){
    if(route.path == '/edit-profile'){
      updateProfile(profile,userId.value);
    }else if(route.path == '/register'){
      setRegisterUser(profile.value);
    }else if(route.path.includes('/admin/users/edit/')){
      console.log('isPenalty',isPenalty.value);
      updateUser(profile.value,userId.value, profile.status);
    }else if(route.path.includes('/admin/users/create')){
      adminStoreUser(profile.value);
    }
  }

  function changeStatus(event) {
    profile.value.status = event.target.value;
  }

  function changeRoles(event) {
    profile.value.role = event.target.value;
    if(profile.value.role == 'user'){
      profile.value.isDealer = false;
    }else if(profile.value.role == 'dealer'){
      profile.value.isDealer = true;
    }
}

  //파일 관련 함수
  function handleFileUploadSign(event) {
    const file = event.target.files[0];
    if (file) {
        profile.value.file_user_sign = file;
        profile.value.file_user_sign_name = file.name;
        fileSignUrl.value = URL.createObjectURL(file);
        //console.log("Signature file:", file.name);
    }
  }

  function handleFileUploadBiz(event) {
    const file = event.target.files[0];
    if (file) {
        profile.value.file_user_biz = file;
        profile.value.file_user_biz_name = file.name;
        fileBizUrl.value = URL.createObjectURL(file);
        //console.log("Business registration file:", file.name);
    }
  }

  function handleFileUploadCert(event) {
    const file = event.target.files[0];
    if (file) {
        profile.value.file_user_cert = file;
        profile.value.file_user_cert_name = file.name;
        fileCertUrl.value = URL.createObjectURL(file);
        //console.log("Certification file:", file.name);
    }
  }

  function handleFileUploadPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        profile.value.file_user_photo = file;
        profile.value.file_user_photo_name = file.name;
        photoUrl.value = URL.createObjectURL(file);
        //console.log("Certification file:", file.name);
    }
  }

  function triggerFileUploadBiz() {
    if (fileInputRefBiz.value) {
        fileInputRefBiz.value.click();
        console.log(fileInputRefBiz.value);
    } else {
        console.error("사업자등록증 파일을 찾을 수 없습니다.");
    }
  }

  function triggerFileUploadSign() {
    if (fileInputRefSign.value) {
        fileInputRefSign.value.click();
    } else {
        console.error("인감 정보 파일을 찾을 수 없습니다.");
    }
  }

  function triggerFileUploadCert() {
    if (fileInputRefCert.value) {
        fileInputRefCert.value.click();
    } else {
        console.error("대표증 또는 종사원증 파일을 찾을 수 없습니다.");
    }
  }

  function triggerFileUploadPhoto() {
    if (fileInputPhoto.value) {
      fileInputPhoto.value.click();
    } else {
        console.error("사진 (본인 확인용) 파일을 찾을 수 없습니다.");
    }
  }

  function deletePhotoImg(){
    profile.value.photoImgChg = true;
    photoUrl.value = profileDom;
  }

  function approveUser(){
    updateUser(profile.value,userId.value, true);
  }

  </script>
  
  <style scoped>
  .profile {
    max-width: 400px;
    margin: 0 auto;
    font-family: Arial, sans-serif;
  }
  
  .form-group {
    margin-bottom: 1rem;
  }
  
  .profile-content {
    display: flex;
    flex-direction: column;
    align-content: center;
    align-items: center;
  }
  
  label {
    display: block;
    margin-bottom: 0.5rem;
  }
  
  input[type="text"],
  input[type="email"],
  input[type="password"]{
    width: 100%;
    padding: 0.5rem;
    box-sizing: border-box;
    border: 1px solid #ccc;
  }
  .profile-photo {
    position: relative !important;
    width: 150px;
  }
  
  .d-none {
    display: none;
  }
  .img-form {
      position: relative;
      display: inline-block;
    }
    .icon-trash-img {
      position: absolute;
      bottom: 18px;
      right: -23px;
      z-index: 10;
      cursor: pointer;
    }

  /**
    회원가입
  */
  .bold-link {
    font-weight: bold;
  }

  .text-secondary opacity-50 {
      color: #c8c8c8 !important;
  }

  .fw-bold {
      font-weight: bold !important;
  }

  .custom-highlight {
      background: linear-gradient(transparent 50%, #ffe7eb 50%);
  }

  .btn-lg {
      font-size: 1rem !important;
  }

  .icon-link {
      color: grey;
      text-decoration: underline !important;
  }
  .form-group input[type=date] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-top: 10px;
}
.search-btn{
  right: 35px;
}
  </style>
  