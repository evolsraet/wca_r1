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
          <label for="name"><span class="text-danger">*</span> 이름</label>
          <input type="text" v-model="profile.name" id="name" class="form-control" placeholder="이름"/>
          <div v-if="registerURL || adminCreateURL || adminEditURL" class="text-danger mt-1">
              <div v-for="message in validationErrors?.name">
                  {{ message }}
              </div>
          </div>
        </div>
        <div v-if="userEditURL || adminEditURL" class="form-group">
          <label for="name"><span class="text-danger">*</span> 전화번호</label>
          <input type="text" id="phone" v-model="profile.phone" class="input-dis form-control" readonly/>
        </div>
        <div v-if="registerURL || adminCreateURL" class="form-group">
          <label for="name"><span class="text-danger">*</span> 전화번호</label>
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
          <label for="email"><span class="text-danger">*</span> 비밀번호</label>
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
          <label for="email"><span class="text-danger" v-if="adminCreateURL || registerURL">*</span> 비밀번호 확인</label>
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
                  <input type="checkbox" id="ch4" v-model="profile.isDealer" class="form-control" ref="isDealerCheck" @change="isDealerCheckEvent">
                  <label for="ch4">네,딜러에요!</label>
              </div>
          </div>

          <!-- <DealerCheckEvent :mode="check1" /> -->

          <p class="text-secondary opacity-50">딜러라면 추가 정보 입력이 필요해요</p>
          <a @click="openModal('privacy')" class="icon-link mt-5 mb-3">
              <img src="../../../img/Icon-file.png" class="ms-2" alt="회원약관 및 개인정보 처리방침">위카모빌리티 회원약관 및 개인정보처리 방침
          </a>

          <div v-if="isDealer || profile.isDealer">
            
            <a @click="isDealerCheckEvent" class="icon-link">
              <img src="../../../img/Icon-file.png" class="ms-2" alt="온라인자동차경매장 규약">온라인자동차경매장 규약
            </a>

            <a @click="isDealerCheckEvent2" class="icon-link mt-3">
              <img src="../../../img/Icon-file.png" class="ms-2" alt="주민등록번호(법인등록번호)고지사항">주민등록번호(법인등록번호) 수집 동의
            </a>

            <a @click="isDealerCheckEvent3" class="icon-link mt-3">
              <img src="../../../img/Icon-file.png" class="ms-2" alt="자동차관리사업등록번호 고지사항">자동차관리사업등록번호 수집 동의
            </a>

            <a @click="isDealerCheckEvent4" class="icon-link mt-3">
              <img src="../../../img/Icon-file.png" class="ms-2" alt="사업자정보">사업자정보 수집 동의
            </a>

            <!-- <DealerPersonalNumber :mode="check2" /> -->

            <div class="form-group dealer-check" style="margin-top: 20px;">
                <label for="dealer">온라인자동차경매장 규약</label>
                <div class="check_box">
                    <input type="checkbox" id="ch5" v-model="profile.isDealerApplyCheck" class="form-control" ref="isDealerApplyCheckSelect">
                    <label for="ch5">동의</label>
                </div>
            </div>


            <div class="form-group dealer-check" style="margin-top: 20px;">
                <label for="dealer">주민등록번호(법인등록번호) 수집 동의</label>
                <div class="check_box">
                    <input type="checkbox" id="ch6" v-model="profile.isDealerApplyCheck1" class="form-control" ref="isDealerApplyCheckSelect1">
                    <label for="ch6">동의</label>
                </div>
            </div>

            <div class="form-group dealer-check" style="margin-top: 20px;">
                <label for="dealer">자동차관리사업등록번호 수집 동의</label>
                <div class="check_box">
                    <input type="checkbox" id="ch7" v-model="profile.isDealerApplyCheck2" class="form-control" ref="isDealerApplyCheckSelect2">
                    <label for="ch7">동의</label>
                </div>
            </div>

            <div class="form-group dealer-check" style="margin-top: 20px;">
                <label for="dealer">사업자정보 수집 동의</label>
                <div class="check_box">
                    <input type="checkbox" id="ch8" v-model="profile.isDealerApplyCheck3" class="form-control" ref="isDealerApplyCheckSelect3">
                    <label for="ch8">동의</label>
                </div>
            </div>

          </div>


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
            <label for="dealerName"><span class="text-danger">*</span> 딜러 이름</label>
            <input type="text" v-model="profile.dealer_name" id="dealerName" class="form-control" placeholder="이름"/>
            <div v-for="message in validationErrors?.name" class="text-danger mt-1">
                  {{ message }}
            </div>
          </div>
          <div v-if="registerURL || adminCreateURL || userEditURL || adminEditURL" class="form-group">
            <label for="name"><span class="text-danger">*</span> 연락처</label>
            <input type="text" id="phone" v-model="profile.dealerContact" class="form-control" placeholder="- 없이 전화번호를 입력해 주세요"/>
            <div v-for="message in validationErrors?.phone" class="text-danger mt-1">
                  {{ message }}
            </div>
          </div>
          <div v-if="registerURL || adminCreateURL || userEditURL || adminEditURL" class="form-group">
              <label for="dealerBirthDate"><span class="text-danger">*</span> 생년월일</label>
              <input type="date" id="dealerBirthDate" v-model="profile.dealerBirthDate" placeholder="1990-12-30">
              <div v-for="message in validationErrors?.birthday" class="text-danger mt-1">
                  {{ message }}
            </div>
          </div>

          <div class="form-group">
            <label for="dealer"><span class="text-danger">*</span> 자동차관리사업등록번호</label>
            <input type="text" v-model="profile.car_management_business_registration_number" class="form-control" placeholder="자동차관리사업등록번호"/>
            <div v-for="message in validationErrors?.car_management_business_registration_number" class="text-danger mt-1">
                  {{ message }}
            </div>
          </div>

          <div class="form-group">
            <label for="dealer"><span class="text-danger">*</span> 사업자번호</label>
            <input type="text" v-model="profile.business_registration_number" class="form-control" placeholder="사업자번호"/>
            <div v-for="message in validationErrors?.business_registration_number" class="text-danger mt-1">
                  {{ message }}
            </div>
          </div>

          <div class="form-group">
            <label for="dealer"><span class="text-danger">*</span> 주민등록번호 또는 법인번호</label>
            <input type="text" v-model="profile.corporation_registration_number" class="form-control" placeholder="주민등록번호 또는 법인번호"/>
            <div v-for="message in validationErrors?.corporation_registration_number" class="text-danger mt-1">
                  {{ message }}
            </div>
          </div>

          <div class="form-group">
            <label for="dealer"><span class="text-danger">*</span> 소속상사</label>
            <input type="text" v-model="profile.company" class="form-control" placeholder="상사명(상사 정식 명칭)"/>
            <div v-for="message in validationErrors?.company" class="text-danger mt-1">
                  {{ message }}
            </div>
          </div>
          <div class="form-group">
            <label for="dealer"><span class="text-danger">*</span> 소속상사 직책</label>
            <input type="text" v-model="profile.dealerCompanyDuty" class="form-control" placeholder="사원"/>
            <div v-for="message in validationErrors?.company_duty" class="text-danger mt-1">
                  {{ message }}
            </div>
          </div>
          <div class="form-group">
            <label for="dealer"><span class="text-danger">*</span> 소속상사 주소 </label>
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
            <label for="dealer"><span class="text-danger">*</span> 소개</label>
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
        <div v-if="registerURL" class="sticky-bottom pb-2">
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
  
 
  <script>
  import flatpickr from "flatpickr";
  import "flatpickr/dist/flatpickr.min.css";
  import { Korean } from 'flatpickr/dist/l10n/ko.js'
  export default {
    data() {
      return {
      };
    },
    methods: {
    },
    mounted() {
      flatpickr("#dealerBirthDate", {
        locale: Korean,
        //enableTime: true,
        dateFormat: "Y-m-d",
        // minDate: "today",
        minDate: new Date(),
        disable: [
          function (date) {
            // 주말 비활성화
            return date.getDay() === 0 || date.getDay() === 6;
          },
        ],
      });
    },
  };
  </script>

  <script setup>
  import { ref, onMounted ,computed , inject,createApp ,h } from 'vue';
  import { useStore } from 'vuex';
  import { useRoute } from 'vue-router';
  import profileDom from '/resources/img/profile_dom.png'; 
  import { cmmn } from '@/hooks/cmmn';
  import useUsers from "@/composables/users";
  import LawGid from '@/views/modal/LawGid.vue';
  import DealerCheckEvent from '@/views/guide/DealerCheckEvent.vue';
  import DealerPersonalNumber from '@/views/guide/DealerPersonalNumber.vue';
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

  const isDealerApplyCheckSelect = ref(null);
  const isDealerApplyCheckSelect1 = ref(null);
  const isDealerApplyCheckSelect2 = ref(null);
  const isDealerApplyCheckSelect3 = ref(null);
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
    car_management_business_registration_number: '',
    business_registration_number: '',
    corporation_registration_number: '',
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

  const isDealerCheck = ref(false);

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
        profile.value.car_management_business_registration_number = user.value.dealer.car_management_business_registration_number;
        profile.value.business_registration_number = user.value.dealer.business_registration_number;
        profile.value.corporation_registration_number = user.value.dealer.corporation_registration_number;
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

  const isDealerCheckEvent = () => {

    if (profile.value.isDealer) {
    const text = `<div style="height: auto; overflow-y: scroll !important; text-align: left;" class="info-popup">
      <div style="height:auto !important">
        <h4 style="color: #333; margin-bottom:10px; text-align: center;">온라인자동차경매장 규약</h4>

        <h4 style="color: #555; margin-bottom:10px;">제 1장 총칙</h4>
        <h5 style="color: #555; margin-bottom:10px;">제 1조 목적</h5>
        <p style="font-size:16px; padding-left:20px;">본 규약은 위카모빌리티가 운영하는 온라인 자동차 경매장 '위카옥션'의 운영 기준을 정하며, 경매장의 투명성과 공정성을 보장하고 원활한 거래를 위한 표준을 제시하는 것을 목적으로 한다.</p>

        <h5 style="color: #555; margin-bottom:10px; margin-top:10px;">제 2조 정의</h5>
        <ul>
            <li style="padding-bottom:5px;">1. '경매'란 등록된 차량이 실시간 경쟁을 통해 최고가에 판매되는 과정을 의미한다.</li>
            <li style="padding-bottom:5px;">2. '출품'이란 판매자가 차량을 위카옥션에 등록하여 경매에 부치는 행위를 말한다.</li>
            <li style="padding-bottom:5px;">3. '낙찰'이란 경매 종료 후 최고 응찰자가 차량을 구매하는 것을 의미한다.</li>
            <li style="padding-bottom:5px;">4. '출품 회원'이란 경매장의 이용약관에 동의하고 가입 절차를 완료한 개인 또는 법인으로 경매장에 출품을 진행하는 자를 의미한다.</li>
            <li style="padding-bottom:5px;">5. '경매회원'이란 경매장의 이용약관에 동의하고 가입 절차를 완료한 개인 또는 법인으로 경매에 참여하고 낙찰을 받을 수 있는 자를 의미한다.</li>
            <li style="padding-bottom:5px;">6. '온라인 경매'란 위카옥션 플랫폼을 통해 원격으로 진행되는 실시간 자동차 경매를 의미한다.</li>
        </ul>

        <h5 style="color: #555; margin-bottom:10px;">제 3조 적용 및 변경</h5>
        <ul>
            <li style="padding-bottom:5px;">- 본 규약은 위카옥션이 운영하는 모든 경매에 적용되며, 모든 참가자는 규약을 준수해야 한다.</li>
            <li style="padding-bottom:5px;">- 규약의 변경이 필요한 경우 위카옥션은 변경 내용을 최소 14일 전 공지하며, 변경 후에도 서비스를 이용하는 회원은 개정된 규약에 동의한 것으로 간주한다.</li>
        </ul>

        <h4 style="color: #333;">제 2장 회원 및 참가 자격</h4>
        <h5 style="color: #555; margin-bottom:10px;">제 4조 회원 자격</h5>
        <p><strong>출품 회원:</strong></p>
        <p style="font-size:16px; padding-left:20px;">경매약관을 동의하고 가입 절차를 완료한 개인 또는 법인으로 차량을 출품을 희망하는 자는 출품회원으로 가입할 수 있다.</p>
        <p><strong>경매회원:</strong></p>
        <ul>
            <li style="padding-bottom:5px;">1) 자동차 매매업 등록을 완료한 개인 및 법인</li>
            <li style="padding-bottom:5px;">2) 자동차 수출업 등록이 완료된 사업자로 위카옥션으로부터 승인을 받은 개인 및 법인</li>
            <li style="padding-bottom:5px;">3) 기타 위카옥션이 정한 자격을 충족하는 자</li>
        </ul>

        <h5 style="color: #555; margin-bottom:10px;">제 5조 회원 종류</h5>
        <p style="font-size:16px; padding-left:20px;">1. 경매장의 회원은 출품, 경매 운영 방침에 따라 출품회원과 경매회원으로 구분한다.</p>
        <ul>
            <li style="padding-bottom:5px;">1) 출품 회원: 경매장에 자동차 출품이 가능한 회원</li>
            <li style="padding-bottom:5px;">2) 경매 회원: 경매에 참여하고 낙찰을 받을 수 있는 회원</li>
        </ul>

        <p style="font-size:16px; padding-left:20px;">2. 경매장 출품 회원과 경매 회원은 아래의 등급으로 분류하여 관리할 수 있다.</p>
        <ul>
            <li style="padding-bottom:5px;">1) 준회원: 경매 출품, 낙찰 권한은 부여하지 않으며, 단순히 경매 열람을 가능하게 하는 회원</li>
            <li style="padding-bottom:5px;">2) 정회원: 경매 출품과 낙찰 권한을 부여한 회원</li>
            <li style="padding-bottom:5px;">3) 특별회원: 일정 기간을 두고 정회원과 같은 권한을 부여한 회원</li>
        </ul>

        <h5 style="color: #555;">제 6조 회원 가입 절차</h5>
        <ul>
            <li>1. 경매 회원은 신청서를 제출하고, 경매장에 정한 절차에 따라 확인을 거친 후 가입이 승인된다.</li>
            <li>2. 출품 회원은 경매장에 정한 절차에 따라 출품 신청서를 작성 후 경매장에 차량을 출품할 수 있다.</li>
        </ul>

        <h5 style="color: #555;">제 7조 유효기간</h5>
        <p style="font-size:16px; padding-left:20px;">회원의 유효기간은 계약일(회원가입일)로부터 1년으로 하고 계약만료 10일 전까지 당사자 쌍방 중 어느 한쪽이 이의를 제기하지 않으면 1년씩 자동 연장된다.</p>

        <h5 style="color: #555; margin-top: 20px;">제 8조 권리와 의무</h5>
        <ul>
            <li style="padding-bottom:5px;">1. 품회원은 경매에 참가하여 차량을 출품할 수 있으며, 경매회원은 차량을 낙찰받을 수 있다.</li>
            <li style="padding-bottom:5px;">2. 경매회원은 경매에 참여할 경우 회원 본인의 인증 절차를 거쳐 경매에 참여할 수 있으며, 개인보안 대처 미비로 인해 발생되는 손해 등 모든 책임은 회원에게 있다.</li>
            <li style="padding-bottom:5px;">3. 경매 참가자는 본 규약을 준수해야 하며, 세칙 등 경매장이 경매의 원활한 운영을 위하여 제정한 규정 및 지시를 준수해야 한다.</li>
            <li style="padding-bottom:5px;">4. 경매 참가자는 경매장이 본 규약에 의거 제안하는 클레임 처리 방안을 성실히 이행해야 하며, 이를 위반, 이행지체 또는 불이행하는 경우 경매장은 회원계약을 해지할 수 있다.</li>
        </ul>

        <h5 style="color: #555;">제 9조 회원정보 변경통보</h5>
        <p style="font-size:16px; padding-left:20px;">경매회원은 상호, 대표자, 사업자번호, 사업장 주소, 업종, 업태, 전화번호, 거래은행, 주소 기타 신고내용에 변경사항이 발생한 경우 신속히 경매장에 해당 사실을 서류 첨부하여 변경 신고해야 하며, 변경사항의 미고지로 인한 모든 피해의 책임은 회원에게 있다.</p>

        <h5 style="color: #555; margin-top: 20px;">제 10조 금지행위</h5>
        <p style="font-size:16px; padding-left:20px;">경매참가자는 아래 각 호에서 정하는 행위를 하여서는 아니 되며, 위반 시 경매장은 해당 경매회원과의 계약을 해지 또는 해제할 수 있다.</p>
        <ul style="margin-top:10px">
            <li style="padding-bottom:5px;">1) 출품차량(유찰차량 포함)에 대해 경매 또는 경매장의 중개에 의하지 않고 직접 거래하는 행위</li>
            <li style="padding-bottom:5px;">2) 회원 명의를 대여하여 경매 응찰을 대행 조작하는 행위</li>
            <li style="padding-bottom:5px;">3) 경매장이 인정하지 않는 자를 경매장에 동반 입장하는 행위</li>
            <li style="padding-bottom:5px;">4) 타인의 응찰을 방해하는 행위</li>
            <li style="padding-bottom:5px;">5) 주행거리 표시기를 조작하거나 그에 관련한 모든 행위</li>
            <li style="padding-bottom:5px;">6) 경매장을 사칭하거나 상표를 도용 또는 모방하는 일체의 행위</li>
            <li style="padding-bottom:5px;">7) 절도, 폭력, 폭언, 기물 파손, 해킹 등 경매장의 업무를 방해하는 행위</li>
            <li style="padding-bottom:5px;">8) 경매규약을 위반하는 행위</li>
            <li style="padding-bottom:5px;">9) 현행법규를 위반하는 행위</li>
            <li style="padding-bottom:5px;">10) 경매장이 정한 규약 또는 지시를 위반하여 그 시정을 최고하였으나, 위반 사항을 시정하지 아니한 경우</li>
            <li style="padding-bottom:5px;">11) 강제 탈퇴 제재 조치를 받은 이력의 회원이 위장하여 가입하는 행위</li>
        </ul>

        <h4 style="color: #333;">제 3장 경매 운영</h4>

        <h5 style="color: #555;">제 11조 출품 절차</h5>
        <p style="font-size:16px; padding-left:20px;">출품자는 차량 상태, 사고 이력 등 주요 정보를 정확히 기재해야 하며, 허위 정보 발견 시 출품이 제한된다.</p>
        <ul style="margin-top:10px">
            <li style="padding-bottom:5px;">1) 출품자는 차량 등록 시 주요 사항(차량의 상태, 사고 이력, 침수 여부, 주행거리, 접합차량, 특이이력, 엔진 및 미션 이상 등 가격에 중대한 영향을 미치는 사항)을 정확히 기재해야 합니다</li>
            <li style="padding-bottom:5px;">2) 위카옥션은 차량의 진단을 거쳐 등록을 승인하며, 허위 정보가 발견될 경우 출품을 제한할 수 있습니다.</li>
            <li style="padding-bottom:5px;">3) 출품자는 출품차량의 소유자이거나 출품차량 소유자로부터 적법한 위임을 받은 자이어야 하며, 처분권을 가진 자로부터 적법한 위임을 받지 않거나 관련 법령에 의하여 부적격자로 판단될 경우에는 경매장은 출품을 제한할 수 있습니다</li>
        </ul>


        <h5 style="color: #555;">제 12조 출품자동차의 조건</h5>
        <p style="font-size:16px; padding-left:20px;">출품 자동차는 다음의 조건을 갖추어야 합니다.</p>
        <ul style="margin-top:10px">
            <li style="padding-bottom:5px;">1) 주행이 가능하고 엔진, 트랜스미션 등 주요 기관에 이상이 없는 차량. 또한, 경매 전 또는 경매 후 엔진 및 트랜스미션 등 주요 기관에 이상이 발생할 경우 출품자의 귀책이 발생할 수 있습니다.</li>
            <li style="padding-bottom:5px;">2) 차량의 운행에 관련된 소모품이 정상 상태인 차량.</li>
            <li style="padding-bottom:5px;">3) 본인 소유 또는 소유자로부터 정당하게 양도 권한을 위임받은 차량.</li>
            <li style="padding-bottom:5px;">4) 압류, 저당 설정 등의 하자가 없거나 낙찰 후 즉시 말소 해제 등의 조치가 가능한 차량.</li>
            <li style="padding-bottom:5px;">5) 구조변경 차량의 경우는 구조변경을 관청 및 검사소 허가를 받은 차량.</li>
            <li style="padding-bottom:5px;">6) 침수, 접합, 전손 이력 차량의 경우 사전에 경매장에 해당 사실을 통보하고 경매장이 출품을 허가하는 차량.</li>
            <li style="padding-bottom:5px;">7) 자동차 의무보험이 가입되어 있는 차량. 자동차 의무보험은 낙찰자가 차량의 명의이전까지는 출품자의 의무보험이 가입되어 있어야 합니다. 단, ‘상품용’ 차량은 낙찰 반출 시 낙찰자, 유찰 반출 시 출품자가 가입하여야 합니다.</li>
        </ul>


        <h5 style="color: #555;">제 13조 경매대상 차량에 대한 진단</h5>
        <p style="font-size:16px; padding-left:20px;">경매대상 자동차에 대한 진단 기준은 첨부된 경매장 차량 진단 기준을 적용한다.</p>
        

        <h5 style="color: #555; margin-top: 20px;">제 14조 경매 방식</h5>
        <ul style="margin-top:10px">
            <li style="padding-bottom:5px;">1. 위카옥션은 실시간 경쟁입찰 방식으로 진행됩니다.</li>
            <li style="padding-bottom:5px;">2. 경매는 출품후 진단완료 익일부터 48시간 진행되며, 경매종료후 48시간 이내 출품회원이 응찰한 경매회원중 최고 회원을 선택하게 되면 낙찰이 됩니다.</li>
            <li style="padding-bottom:5px;">3. 진행시 출품회원이 경매회원을 선택하지 않을 경우 유찰이 됩니다.</li>
            <li style="padding-bottom:5px;">4. 낙찰확정일로부터 낙찰자는 48시간이내 인수정보를 입력하여야 하며, 탁송은 최대 5일내로 고객과 날짜가 정해집니다. 단, 48시간이내 인수정보를 입력하지 않으면 낙찰이 취소됩니다.</li>
        </ul>

        <h5 style="color: #555;">제 15조 낙찰대금 입금 및 정산</h5>
        <ul style="margin-top:10px">
            <li style="padding-bottom:5px;">1. 경매회원은 탁송 2시간 전까지 에스크로 계좌에 차량대금을 입금해야 하며, 2시간 전까지 입금이 완료되지 않으면 낙찰이 취소됩니다.</li>
            <li style="padding-bottom:5px;">2. 차량 대금의 정산은 이전에 필요한 서류를 출품회원이 탁송자에게 교부하면 차량대금이 에스크로 계좌에서 출품회원에게 출금됩니다.</li>
            <li style="padding-bottom:5px;">3. 경매 낙찰에 따른 소정의 수수료는 첨부에 따라 경매회원에게 부과되며 수수료 지급이 되지 않을 경우 경매 참여가 정지됩니다.</li>
        </ul>

        <h5 style="color: #555;">제 16조 명의 이전</h5>
        <ul style="margin-top:10px">
            <li style="padding-bottom:5px;">1. 경매회원은 탁송완료시점부터 2영업일 이내에 명의 이전을 해야 합니다.</li>
            <li style="padding-bottom:5px;">2. 명의이전이 지연될 경우 지연 배상금이 부과되며, 30일 이상 경과 시 강제 이전 절차가 진행될 수 있습니다.</li>
        </ul>

        <h5 style="color: #555;">제 17조 낙찰 취소 위약금</h5>
        <ul style="margin-top:10px">
            <li style="padding-bottom:5px;">1. 낙찰자가 부득이한 경우 낙찰 취소가 필요한 경우 낙찰일로부터 2영업일 내로 낙찰을 취소할 수 있습니다.</li>
            <li style="padding-bottom:5px;">2. 낙찰자는 취소 위약금을 지불해야 하며, 취소하는 경우 최소 위약금은 아래와 같습니다.</li>
        </ul>

        <ul style="margin-left:5px">
            <li style="padding-bottom:5px;">1) 낙찰금액의 10‰</li>
            <li style="padding-bottom:5px;">2) 최소 10만원, 최대 200만원</li>
        </ul>

        <ul style="margin-top:10px">
            <li style="padding-bottom:5px;">3. 취소 위약금중 60‰ 출품자에게 지급합니다.</li>
            <li style="padding-bottom:5px;">4. 명의이전이 완료된 경우 낙찰 취소는 불가합니다.</li>
        </ul>

        <h5 style="color: #555;">제 18조 경매진행 및 낙찰 유도</h5>
        <p style="font-size:16px; padding-left:20px;">위카옥션은 경매의 원활한 진행 및 낙찰 유도를 위해 경매행위에 개입할 수 있으며, 경매출품 차량에 대한 별도 상담 및 주선, 알선을 진행할 수 있습니다.</p>

        <h4 style="color: #333; margin-top: 20px;">제 4장 클레임 및 분쟁 해결</h4>

        <h5 style="color: #555;">제 19조 클레임 신청</h5>
        <ul style="margin-top:10px">
            <li style="padding-bottom:5px;">1. 낙찰자는 출품정보와 실제 차량 상태가 상이할 경우 2일 이내 클레임을 신청할 수 있습니다. 단, 명의 이전이 완료된 경우는 클레임을 신청할 수 없습니다.</li>
            <li style="padding-bottom:5px;">2. 클레임 신청은 진단 결과에 대한 오류에 한하며, 경미한 사항 및 출품회원이 미고지한 중대 결함에 대한 클레임 신청 대상에서 제외됩니다.</li>
            <li style="padding-bottom:5px;">3. 10년 이상 또는 주행거리 200,000km 이상인 차량은 클레임 대상에서 제외됩니다.</li>
            <li style="padding-bottom:5px;">4. 냄새, 이음, 진동 등에 대한 주관적인 문제 제기는 클레임 대상에서 제외됩니다.</li>
        </ul>

        <h5 style="color: #555;">제 20조 클레임 처리 기준</h5>
        <ul style="margin-top:10px">
            <li style="padding-bottom:5px;">1. 차량 상태에 대해 인지하였으나 사전에 미고지된 경우 출품자가 보상 또는 환불해야 합니다.</li>
            <li style="padding-bottom:5px;">2. 진단 오류로 인정되는 경우 감가 항목별 보상 기준에 따라 보상 처리하며, 보상 한도는 100만원입니다.</li>
            <li style="padding-bottom:5px;">3. 분쟁 발생 시 위카옥션의 중재를 따르며, 중재 거부 시 법적 절차를 통해 해결할 수 있습니다.</li>
            <li style="padding-bottom:5px;">4. 클레임으로 인해 낙찰을 취소하게 되는 경우, 진단업체에서 낙찰 취소 위약금을 지불하는 조건으로 낙찰 취소를 진행할 수 있으며, 출품회원은 반드시 위약금을 지불하는 조건으로 차량을 인수받아야 합니다.</li>
        </ul>

        <h5 style="color: #555;">제 21조 클레임 면책</h5>
        <p style="font-size:16px; padding-left:20px;">아래 각 호에 해당하는 경우 경매장은 클레임에 대해 면책됩니다. </p>
        <ul style="margin-top:10px">
            <li style="padding-bottom:5px;">1. 차량 탁송 완료 이후 발생한 비용(추가 탁송료, 상품화비, 명의이전 완료 차량에 대한 등록 비용, 자동차 진단 비용 등) 또는 하자</li>
            <li style="padding-bottom:5px;">2. 비내구성 부품의 결품 또는 결함</li>
            <li style="padding-bottom:5px;">3. 주행거리계 교환 또는 주행거리 변경을 명기한 차량</li>
            <li style="padding-bottom:5px;">4. 회원탈퇴 이후 또는 회원이 폐업한 경우</li>
            <li style="padding-bottom:5px;">5. 본인이 출품한 차량의 가격을 의도적으로 인상시키기 위한 행위로 인한 본인 낙찰 차량</li>
            <li style="padding-bottom:5px;">6. 출품자가 출품 차량의 침수, 주행거리 조작 및 주행거리 상이 이력, 특이 경력(영업용, 렌터카 등), 사고 등 가격에 큰 영향을 미치는 주요 사항을 경매장에 고지하지 않거나 허위 기입, 오기입, 기입 누락한 경우</li>
            <li style="padding-bottom:5px;">7. 출품 차량이 10년 이상이거나 주행거리 200,000km 이상인 경우</li>
            <li style="padding-bottom:5px;">8. 진단 이후 차량을 1,000km 이상 주행한 경우</li>
        </ul>

        <h5 style="color: #555;">제 22조 책임 면제</h5>
        <ul style="margin-top:10px">
            <li style="padding-bottom:5px;">1. 경매 시스템 장애, 네트워크 오류 등으로 인해 발생한 문제에 대해 위카옥션은 책임지지 않습니다.</li>
            <li style="padding-bottom:5px;">2. 출품자가 차량 정보를 허위로 기재한 경우, 위카옥션은 경매회원 보호를 위해 출품자의 자격을 박탈할 수 있습니다.</li>
        </ul>

        <h4 style="color: #333; margin-top: 20px;">제 5장 기타</h4>
        
        <h5 style="color: #555;">제 23조 규약의 개정</h5>
        <p style="font-size:16px; padding-left:20px;">이 규약 및 관련 규정은 경매장이 필요한 경우 개정할 수 있으며, 경매장은 규약 개정 시 개정 내용 및 효력 발생일을 경매 참가자에게 고지하여야 합니다. (고지 방법: 홈페이지 게시)</p>
        
        <h5 style="color: #555; margin-top: 20px;">제 24조 관할법원</h5>
        <p style="font-size:16px; padding-left:20px;">이 규약에 명시되지 않은 사항은 관계법령 및 일반 상 관례에 따라 상호 협의하여 처리하되, 분쟁이 발생한 경우 소를 제기하는 자의 상대방 주소지를 관할하는 법원 또는 경매장 본사 소재지의 법원을 관할 법원으로 합니다</p>

        <h4 style="color: #333; margin-top: 20px;">제 6장 부칙</h4>
        <h5 style="color: #555;">제 26조 규약의 개정 및 시행</h5>
        <ul>
            <li style="padding-bottom:5px;">- 본 규약은 2025년 3월 1일부터 시행됩니다.</li>
            <li style="padding-bottom:5px;">- 본 규약에서 정하지 않은 사항은 관계 법령 및 위카옥션의 정책에 따릅니다.</li>
        </ul>

        <h4 style="color: #333; margin-top: 20px; margin-bottom: 10px;">별첨 1: 진단 오류 보상 기준</h4>
        <h5 style="color: #555;">1. 진단 결과에 중대한 오류가 있다면, 인수 후 영업일 기준 2일 내 클레임이 가능합니다.</h5>
        <h5 style="color: #555;">2. 보상 제외</h5>
        <ul>
            <li style="padding-bottom:5px;">1) 현장 진단 특성상 아래 사항은 클레임 대상에서 제외됩니다</li>
            <li style="padding-bottom:5px;">2) 문콕, 생활기스, 사용감, 미세누유, 소모품 상태, 썬팅 손상 등 경미한 사항</li>
            <li style="padding-bottom:5px;">3) 단순 판금/도장, 라디에이터 서포트, 크로스멤버 교환 풀림, FRP 또는 비금속 재질의 교환/파손 등</li>
            <li style="padding-bottom:5px;">4) 하체, 조향, 제동 등의 소모성 부품 관련</li>
            <li style="padding-bottom:5px;">5) 하부 진단이 반드시 필요한 누유</li>
            <li style="padding-bottom:5px;">6) 자동차 제작사에서 보증 수리가 가능한 기능 문제</li>
            <li style="padding-bottom:5px;">7) 정지 상태에서는 판단 불가한 고장(주행 중에 확인 가능한 고장)</li>
            <li style="padding-bottom:5px;">8) 출품 차량이 10년 이상이거나 주행거리 200,000km 이상인 경우</li>
            <li style="padding-bottom:5px;">9) 냄새, 이음, 진동 등에 대한 주관적인 문제 제기</li>
        </ul>

        <h5 style="color: #555;">3. 감가 항목별 보상 기준</h5>
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr>
                <td style="width: 30%; border: 1px solid #000; text-align: center; padding: 10px; background-color: #f0f0f0;">감가 항목</td>
                <td style="width: 30%; border: 1px solid #000; text-align: center; padding: 10px; background-color: #f0f0f0;">감가율</td>
                <td style="width: 40%; border: 1px solid #000; text-align: center; padding: 10px; background-color: #f0f0f0;">비고</td>
            </tr>
          </thead>
          <tbody>
            <tr>
                <td style="border: 1px solid #000; text-align: center;">후드</td>
                <td style="border: 1px solid #000; text-align: center;">3%</td>
                <td style="border: 1px solid #000; text-align: center;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; text-align: center;">프론트휀더</td>
                <td style="border: 1px solid #000; text-align: center;">2%</td>
                <td style="border: 1px solid #000; text-align: center;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; text-align: center;">도어</td>
                <td style="border: 1px solid #000; text-align: center;">2%</td>
                <td style="border: 1px solid #000; text-align: center;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; text-align: center;">트렁크리드</td>
                <td style="border: 1px solid #000; text-align: center;">2%</td>
                <td style="border: 1px solid #000; text-align: center;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; text-align: center;">쿼터패널</td>
                <td style="border: 1px solid #000; text-align: center;">3%</td>
                <td style="border: 1px solid #000; text-align: center;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; text-align: center;">루프패널</td>
                <td style="border: 1px solid #000; text-align: center;">6%</td>
                <td style="border: 1px solid #000;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; text-align: center;">사이드실패널</td>
                <td style="border: 1px solid #000; text-align: center;">3%</td>
                <td style="border: 1px solid #000; text-align: center;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; text-align: center;">프론트패널</td>
                <td style="border: 1px solid #000; text-align: center;">3%</td>
                <td style="border: 1px solid #000; text-align: center;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; text-align: center;">인사이드패널</td>
                <td style="border: 1px solid #000; text-align: center;">3%</td>
                <td style="border: 1px solid #000; text-align: center;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; text-align: center;">사이드멤버</td>
                <td style="border: 1px solid #000; text-align: center;">3%</td>
                <td style="border: 1px solid #000; text-align: center;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; text-align: center;">휠하우스</td>
                <td style="border: 1px solid #000; text-align: center;">5%</td>
                <td style="border: 1px solid #000; text-align: center;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; text-align: center;">필러패널</td>
                <td style="border: 1px solid #000; text-align: center;">3%</td>
                <td style="border: 1px solid #000; text-align: center;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; text-align: center;">대쉬패널</td>
                <td style="border: 1px solid #000; text-align: center;">3%</td>
                <td style="border: 1px solid #000; text-align: center;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; text-align: center;">트렁크플로어</td>
                <td style="border: 1px solid #000; text-align: center;">3%</td>
                <td style="border: 1px solid #000; text-align: center;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; text-align: center;">리어패널</td>
                <td style="border: 1px solid #000; text-align: center;">3%</td>
                <td style="border: 1px solid #000; text-align: center;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; text-align: center;">트렁크패널</td>
                <td style="border: 1px solid #000; text-align: center;">3%</td>
                <td style="border: 1px solid #000; text-align: center;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; text-align: center;">라디에이터 서포트</td>
                <td style="border: 1px solid #000; text-align: center;">1%</td>
                <td style="border: 1px solid #000; text-align: center;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; text-align: center;">패키지트레이</td>
                <td style="border: 1px solid #000; text-align: center;">3%</td>
                <td style="border: 1px solid #000; text-align: center;"></td>
            </tr>
            <tr>
                <td colspan="3" style="border: 1px solid #000;">
                  <ul style="margin-top: 10px;">
                    <li>- 클레임 보상: 매입 금액 × 감가율 = 보상 금액</li>
                    <li>- 교환/판금에 대한 정의는 자동차관리법상 성능상태점검기준에 따름</li>
                    <li>- 판금오류 시 보상 비율 교환 대비 50% 감가 적용</li>
                    <li>- 사이드멤버, 휠하우스, 인사이드 패널은 좌우앞뒤와 상관없이 단일 품목으로 감가</li>
                  </ul>  
                </td>
            </tr>
            <tr>
          </tbody>
        </table>

        <h5 style="color: #555; margin-top: 20px;">4. 비내구성 부품(소모품) 예시</h5>
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr>
              <td style="width: 30%; border: 1px solid #000; text-align: center; padding: 10px; background-color: #f0f0f0;">구분</td>
              <td style="width: 70%; border: 1px solid #000; text-align: center; padding: 10px; background-color: #f0f0f0;">주변 장치</td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style="border: 1px solid #000; text-align: center;">엔진</td>
              <td style="border: 1px solid #000; text-align: left; padding: 10px;">엔진 및 엔진 전장품 일체 (제너레이터, 콤프레셔, 인젝터, 각종 모터류, 예열장치, 케이블, 배선류, 센서류, 릴레이, 점화 플러그, 배전기, 스위치류 등) 냉각장치 (라디에이터) 터보</td>
            </tr>
            <tr>
              <td style="border: 1px solid #000; text-align: center;">변속기추진축</td>
              <td style="border: 1px solid #000; text-align: left; padding: 10px;">클러치 케이블 및 변속 조작장치, 입/출력 센서, 인히비터 스위치 등</td>
            </tr>
            <tr>
              <td style="border: 1px solid #000; text-align: center;">앞뒤 차축</td>
              <td style="border: 1px solid #000; text-align: left; padding: 10px;">현가장치 부품 (쇼바[전자, 에어 등], 판스프링 등) 제동장치 부품 (라이닝, 드럼, 브레이크 디스크 등) 조향장치 부품 (웜기어, MDPS, 조인트 등) ※ 휠 허브, 너클, 킹핀, 볼조인트, 허브베어링 등 앞뒤 차축 관련 부품 포함</td>
            </tr>
            <tr>
              <td style="border: 1px solid #000; text-align: center;">기타</td>
              <td style="border: 1px solid #000; text-align: left; padding: 10px;">배터리(AGM 포함), 고무부트, 상시 4WD, 연료펌프, 연료필터, 플랜저, 벨트류, 엔진/미션 마운팅, 소음기, 오일쿨러, 크로스멤버, 타이어, 휠 등 주기적인 교환 필요 부품의 결품 또는 결함</td>
            </tr>            
          </tbody>
        </table>

        <h4 style="color: #333; margin-top: 20px;">별첨 2: 수수료 기준</h4>
        <h5 style="color: #555;">낙찰 완료 시 수수료 지급 기준은 아래와 같습니다. (VAT 별도)</h5>
        <ul>
          <li style="padding-bottom:5px;">- 100만원 이하: 14만원</li>
          <li style="padding-bottom:5px;">- 500만원 이하: 25만원</li>
          <li style="padding-bottom:5px;">- 1,000만원 이하: 30만원</li>
          <li style="padding-bottom:5px;">- 3,000만원 이하: 35만원</li>
          <li style="padding-bottom:5px;">- 2,000만원 이상: 40만원</li>
        </ul>
        </div>
      </div>
    `;

    wica.ntcn(swal)
      .useHtmlText() // HTML 태그 활성화
      .useClose()
      .addClassNm('intro-modal') // 클래스명 설정
      // .addClassNm('intromodal-popup') // 클래스명 설정
      .addOption({ padding: 20, height:840 }) // swal 옵션 추가
      .callback(function (result) {
        // 결과 처리 로직
      })
      .confirm(text); // 모달 내용 설정
    }



  }


  const isDealerCheckEvent2 = (() => {


    if (profile.value.isDealer) {
      const text = `<div style="height: 500px; overflow-y: auto; text-align: left;" class="info-popup">
        <h4 style="color: #333; margin-bottom:10px;">주민등록번호(법인번호) 수집 및 이용 동의</h4>

        <p style="font-size:16px; padding-left:20px; text-align: left;">차량 명의이전 행정처리를 위해 아래와 같이 주민등록번호(또는 법인등록번호)를 수집·이용합니다.</p>

        <h5 style="color: #555; margin-bottom:10px; margin-top:10px;">1. 수집</h5>
        <ul>
            <li style="padding-bottom:5px;">- 개인: 주민등록번호</li>
            <li style="padding-bottom:5px;">- 법인: 법인등록번호</li>
        </ul>

        <h5 style="color: #555; margin-bottom:10px;">2. 수집목적</h5>
        <ul>
            <li style="padding-bottom:5px;">- 자동차관리법 등 관계 법령에 따라 차량 명의이전 신청 및 서류 검토.</li>
            <li style="padding-bottom:5px;">- 본인 식별 및 실명 확인.</li>
        </ul>

        <h5 style="color: #555; margin-bottom:10px;">3. 수집 근거</h5>
        <ul>
            <li style="padding-bottom:5px;">- 주민등록법 제29조 제1항</li>
            <li style="padding-bottom:5px;">- 자동차관리법 시행규칙 제25조</li>
        </ul>

        <h5 style="color: #555; margin-bottom:10px;">4. 보유 및 이용기간</h5>
        <ul>
            <li style="padding-bottom:5px;">- 명의이전 처리 완료 후 5년 (전자상거래법에 따른 보존 의무 포함)</li>
        </ul>

        <h5 style="color: #555; margin-bottom:10px;">5. 동의 거부 권리 및 불이익</h5>
        <ul>
            <li style="padding-bottom:5px;">- 귀하는 본 수집·이용에 대해 동의하지 않으실 수 있으나, 이 경우 명의이전 신청이 불가능합니다.</li>
        </ul>

        <h5 style="color: #555; margin-bottom:10px;">6. 주민등록법 제 29조 제 1항</h5>
        <p style="font-size:16px; padding-left:20px;">「주민등록법」 제29조 제1항에 따라, 주민등록표(등본 또는 초본)의 열람 또는 교부는 정해진 수수료 납부 후, 시장·군수·구청장(읍·면·동장 포함)에게 신청할 수 있습니다.</p>
        

        <h5 style="color: #555; margin-bottom:10px; margin-top:20px;">7. 자동차관리법 시행규칙 제25조</h5>
        <p style="font-size:16px; padding-left:20px;">1. 방치자동차는 원칙적으로 일반경쟁입찰을 통해 매각됩니다. 단, 아래의 경우에는 수의계약으로 매각할 수 있습니다.</p>
        <ul>
            <li style="padding-bottom:5px;">1) 매각 예정가격이 500만 원 이하인 경우</li>
            <li style="padding-bottom:5px;">2) 2회 이상 일반경쟁입찰이 유찰된 경우</li>
        </ul>

        <p style="font-size:16px; padding-left:20px;">2. 일반경쟁입찰로 매각 시, 매각 예정일 7일 전까지 다음의 내용이 공고됩니다.</p>
        <ul>
            <li>1. 매각 대상 자동차의 등록번호 및 주요 제원</li>
            <li>2. 매각 장소 및 매각 일시</li>
        </ul>

        <p style="font-size:16px; padding-left:20px;">3. 매각 완료 시, 다음의 내용을 매수인에게 서면 통지합니다.</p>
        <ul>
            <li>1. 매각된 자동차의 등록번호 및 매각일시</li>
            <li>2. 매각 과정</li>
            <li>3. 매수인의 성명 및 주소 (법인의 경우 명칭 및 대표자 포함)</li>
        </ul>

      </div>
    `;

        wica.ntcn(swal)
      .useHtmlText() // HTML 태그 활성화
      .useClose()
      .addClassNm('intro-modal') // 클래스명 설정
      // .addClassNm('intromodal-popup') // 클래스명 설정
      .addOption({ padding: 20, height:840 }) // swal 옵션 추가
      .callback(function (result) {
        // 결과 처리 로직
      })
      .confirm(text); // 모달 내용 설정
    }

  });


  const isDealerCheckEvent3 = (() => {

    if (profile.value.isDealer) {
      const text = `<div style="height: 500px; overflow-y: auto; text-align: left;" class="info-popup">
        <h4 style="color: #333; margin-bottom:10px;">자동차관리사업등록번호 수집 및 이용 동의</h4>

        <p style="font-size:16px; padding-left:20px; text-align: left;">자동차관리사업 등록번호는 자동차관리사업을 하기 위해 시장, 군수, 구청장에게 등록할 때 부여받는 번호입니다. 이 번호는 자동차관리법 및 관련 시행규칙에 따라 관리됩니다. 예를 들어, 자동차매매업을 하려면 자동차관리사업 등록을 해야 하며, 이때 등록번호를 부여받게 됩니다.</p>

        <h5 style="color: #555; margin-bottom:10px; margin-top:10px;">자동차관리법</h5>
        <ul>
            <li style="padding-bottom:5px;">자동차관리사업 등록의 기준 및 절차 등에 대해 규정합니다</li>
        </ul>

        <h5 style="color: #555; margin-bottom:10px;">자동차관리법 시행령</h5>
        <ul>
            <li style="padding-bottom:5px;">자동차관리사업 등록 관련 사항을 구체적으로 규정합니다.</li>
        </ul>

        <h5 style="color: #555; margin-bottom:10px;">자동차관리법 시행규칙</h5>
        <ul>
            <li style="padding-bottom:5px;">자동차관리사업 등록 시 필요한 서류, 시설 기준 등 구체적인 사항을 규정합니다</li>
        </ul>

        <h5 style="color: #555; margin-bottom:10px;">시·도 조례</h5>
        <ul>
            <li style="padding-bottom:5px;">자동차관리사업 등록 기준 및 절차에 대해 시·도 차원에서 추가적으로 규정할 수 있습니다.</li>
        </ul>


        <h4>자동차관리사업 등록번호의 활용</h4>

        <h5 style="color: #555; margin-bottom:10px;">자동차관리사업자 확인</h5>
        <ul>
            <li style="padding-bottom:5px;">등록번호는 자동차관리사업자가 자동차관련 사업을 적법하게 영위하고 있는지 확인하는 수단으로 활용됩니다.</li>
        </ul>

        <h5 style="color: #555; margin-bottom:10px;">자동차 관련 정보 관리</h5>
        <p style="font-size:16px; padding-left:20px;">등록번호는 자동차의 소유자, 차량 정보 등을 체계적으로 관리하는 데 사용됩니다.</p>
        
        <h5 style="color: #555; margin-bottom:10px;">세금 및 과태료 징수</h5>
        <p style="font-size:16px; padding-left:20px;">자동차관리사업과 관련된 세금 및 과태료 징수에 활용됩니다.</p>

        <h5 style="color: #555; margin-bottom:10px; margin-top:20px;">자동차관리사업 등록절차</h5>
        <ul>
            <li style="padding-bottom:5px;"><strong>사업자등록</strong> <br/>자동차관리사업을 하려는 자는 사업자등록증을 발급받아야 합니다.</li>
            <li style="padding-bottom:5px;"><strong>등록신청</strong> <br/>자동차관리사업 등록을 신청할 때 필요한 서류를 갖춰 시장, 군수, 구청장에게 신청합니다.</li>
            <li style="padding-bottom:5px;"><strong>등록번호 부여</strong> <br/>신청 후 등록기준에 적합한 경우 등록번호가 부여됩니다.</li>
        </ul>
      </div>
    `;

        wica.ntcn(swal)
      .useHtmlText() // HTML 태그 활성화
      .useClose()
      .addClassNm('intro-modal') // 클래스명 설정
      // .addClassNm('intromodal-popup') // 클래스명 설정
      .addOption({ padding: 20, height:840 }) // swal 옵션 추가
      .callback(function (result) {
        // 결과 처리 로직
      })
      .confirm(text); // 모달 내용 설정
    }
  });


  const isDealerCheckEvent4 = (() => {
    const text = `<div style="height: 500px; overflow-y: auto; text-align: left;" class="info-popup">
      <h4 style="color: #333; margin-bottom:10px;">사업자정보 수집 및 이용 동의</h4>

        <h5 style="color: #555; margin-bottom:10px; margin-top:10px;">수집하는 항목</h5>
        <ul>
            <li style="padding-bottom:5px;">- 사업자등록번호</li>
            <li style="padding-bottom:5px;">- 사업자명(회사명)</li>
            <li style="padding-bottom:5px;">- 대표자명</li>
            <li style="padding-bottom:5px;">- 사업장 주소</li>
            <li style="padding-bottom:5px;">- 사업자등록증 사본 (필요 시)</li>
        </ul>

        <h5 style="color: #555; margin-bottom:10px; margin-top:10px;">수집 및 이용 목적</h5>
        <ul>
            <li style="padding-bottom:5px;">- 회원 식별 및 사업자 인증 절차 진행</li>
            <li style="padding-bottom:5px;">- 세금계산서 발행 및 법적 의무 이행</li>
            <li style="padding-bottom:5px;">- 부정이용 방지 및 서비스 이용 이력 관리</li>
            <li style="padding-bottom:5px;">- 매도용 자동차 정보 수집 및 관리</li>
        </ul>

        <h5 style="color: #555; margin-bottom:10px; margin-top:10px;">보유 및 이용 기간</h5>
        <ul>
            <li style="padding-bottom:5px;">- 서비스 이용 기간 동안 보유</li>
            <li style="padding-bottom:5px;">- 탈퇴 시 즉시 파기하되, 관련 법령(전자상거래법, 국세기본법 등)에 따라 일정 기간 보존할 수 있음 (5년)</li>
        </ul>

        <h5 style="color: #555; margin-bottom:10px; margin-top:10px;">동의 거부 권리 및 불이익</h5>
        <ul>
            <li style="padding-bottom:5px;">- 이용자는 사업자정보 수집 및 이용에 대해 동의를 거부할 수 있습니다.</li>
            <li style="padding-bottom:5px;">- 다만, 동의 거부 시 회원 가입 또는 일부 서비스 이용이 제한될 수 있습니다.</li>
        </ul>

        <h5 style="color: #555; margin-bottom:10px; margin-top:10px;">관련 법령</h5>
        <ul>
            <li style="padding-bottom:5px;">- 개인정보보호법</li>
            <li style="padding-bottom:5px;">- 전자상거래 등에서의 소비자보호에 관한 법률</li>
            <li style="padding-bottom:5px;">- 국세기본법 등</li>
        </ul>

    </div>
    `;

    wica.ntcn(swal)
    .useHtmlText() // HTML 태그 활성화
    .useClose()
    .addClassNm('intro-modal') // 클래스명 설정
    .addOption({ padding: 20, height:840 }) // swal 옵션 추가
    .callback(function (result) {
    })
    .confirm(text); // 모달 내용 설정
  });


  // function isDealerApplyCheckEvent(){
  //   console.log('isDealerApplyCheck',isDealerApplyCheck);
  //   if(isDealerApplyCheck.value){
  //     isDealerApplyCheck = true;
  //   }else{
  //     isDealerApplyCheck = false;
  //   }

  //   alert('경매장 규약 동의 체크 확인', isDealerApplyCheck);
  // }
  
  onMounted(async () => {
    //await store.dispatch("auth/getUser");
    const urlParams = new URLSearchParams(window.location.search);
    const typeParam = urlParams.get('type');
    const socialParam = urlParams.get('social');
    const emailParam = urlParams.get('email');
    const nameParam = urlParams.get('name');
    if(socialParam){
      profile.value.email = emailParam;
      profile.value.name = nameParam;
    }

    if(typeParam == 'dealer'){
      isDealerApplyCheckSelect.value = false;
      profile.value.isDealerApplyCheck = false;
      isDealerApplyCheckSelect1.value = false;
      profile.value.isDealerApplyCheck1 = false;
      isDealerApplyCheckSelect2.value = false;
      profile.value.isDealerApplyCheck2 = false;
      isDealerApplyCheckSelect3.value = false;
      profile.value.isDealerApplyCheck3 = false;
      profile.value.isDealer = true;
    }

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

      if(profile.value.isDealer){
        if(!profile.value.isDealerApplyCheck){
          isDealerApplyCheckSelect.value.focus();

          wica.ntcn(swal)
          .icon('W')
          .addClassNm('cmm-review-custom')
          .addOption({ padding: 20})
          .callback(function(result) {
          })
          .alert('온라인자동차경매장 규약 내용을 읽어보시고 체크해주세요.');
          return;
        }

        if(!profile.value.isDealerApplyCheck1){
          isDealerApplyCheckSelect1.value.focus();

          wica.ntcn(swal)
          .icon('W')
          .addClassNm('cmm-review-custom')
          .addOption({ padding: 20})
          .callback(function(result) {
          })
          .alert('주민등록번호 또는 법인번호 수집 동의 내용을 읽어보시고 체크해주세요.');
          return;
        }

        if(!profile.value.isDealerApplyCheck2){
          isDealerApplyCheckSelect2.value.focus();

          wica.ntcn(swal)
          .icon('W')
          .addClassNm('cmm-review-custom')
          .addOption({ padding: 20})
          .callback(function(result) {
          })
          .alert('자동차관리사업등록번호 수집 동의 내용을 읽어보시고 체크해주세요.');
          return;
        }

        if(!profile.value.isDealerApplyCheck3){
          isDealerApplyCheckSelect3.value.focus();

          wica.ntcn(swal)
          .icon('W')
          .addClassNm('cmm-review-custom')
          .addOption({ padding: 20})
          .callback(function(result) {
          })
          .alert('사업자정보 수집 동의 내용을 읽어보시고 체크해주세요.');
          return;
        }


      }

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
.info-popup p {
  font-size: 16px !important;
}

.info-popup ul li {
  margin-bottom: 10px !important;
}


.flatpickr-calendar {
  font-family: "Arial", sans-serif;
}

.flatpickr-time .flatpickr-time-separator {
  color: #7a3535;
}
input[type="date"]::-webkit-datetime-edit { 
    color: #aaa; /* 비활성화된 입력에 회색 스타일 적용 */
  }
.flatpickr-day.flatpickr-disabled {
  cursor: not-allowed;
  color: rgba(57, 57, 57, 0.3);
  background: transparent;
  border-color: transparent;
}
/* 비활성화된 날짜 */
.flatpickr-day.flatpickr-disabled {
  cursor: not-allowed;
  color: #d3d3d3;
  background-color: #f9f9f9;
}

/* 활성화된 날짜 */
.flatpickr-day {
  cursor: pointer;
  color: #333333;
}

input[type="date"] {
  width: 100%;
  padding: 8px;
  font-size: 16px;
  box-sizing: border-box;
}

#dealerBirthDate {
    height: 42px;
}

/* @media screen and (min-width: 768px) {
  .intromodal-popup-popup{
    width: 500px !important;
    height: 500px !important;
  }  
} */



  </style>
  