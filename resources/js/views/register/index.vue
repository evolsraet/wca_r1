<template>
    <div>
        <div class="image-container">
            <img src="../../../img/car-objects-blur.png" alt="Animated Image" class="animated-image-1">
            <img src="../../../img/modal/car-objects-blur.png" alt="Second Animated Image" class="animated-image-2">
        </div>
        <!-- Page content -->
        <div class="container">
            <div class="register-content">
                <div v-if="!isMobileView">
                    <div class="any-content">
                        <div class="review-any"></div>
                    </div>
                    <div :class="animationClass" ref="animatedSection">
                        <div class="css-ifyyt1 gap-5">
                            <div class="font-title">
                                <h5 class="text-secondary opacity-50 font-title">내 차 판매에</h5>
                                <h5 class="font-title text-secondary opacity-50">
                                    <span class="tc-red fw-semibold">28% </span>정도
                                    <span class="break-line"></span>  
                                    <span class="ellipsis-animation">가까워지는 중</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="video-container">
                    <video width="90%" class="video_type01" autoplay loop muted>
                        <source src="../../../img/video/register_vi.mp4" type="video/mp4">
                    </video>
                </div>
                <div class="card-section">
                    <div class="card">
                        <div class="card-body">
                            <div class="registration-container">
                                <div class="text-left">
                                    <p class="mb-4 fw-bold">회원 정보를 입력해주세요</p>
                                </div>
                                <div class="form-body">
                                    <form @submit.prevent="submitRegister">
                                        <div class="form-group">
                                            <label for="name">이름</label>
                                            <input autocomplete="one-time-code" v-model="registerForm.name" id="name" type="name" class="form-control" placeholder="이름">
                                            <div class="text-danger mt-1">
                                                <div v-for="message in validationErrors?.name">
                                                    {{ message }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">이메일</label>
                                            <input autocomplete="one-time-code" type="email" v-model="registerForm.email" @input="checkEmail" id="email" class="form-control" placeholder="example@demo.com">
                                            <div class="text-danger mt-1">
                                                <div v-for="message in validationErrors?.email">
                                                    {{ message }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">전화번호</label>
                                            <input autocomplete="one-time-code" type="phone" v-model="registerForm.phone" id="phone" class="form-control" placeholder="- 없이 전화번호를 입력해 주세요">
                                            <div class="text-danger mt-1">
                                                <div v-for="message in validationErrors?.phone">
                                                    {{ message }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">비밀번호</label>
                                            <input autocomplete="one-time-code" v-model="registerForm.password" id="password" type="password" class="form-control" placeholder="6~8자리 숫자,영어,특수문자 혼합">
                                            <div class="text-danger mt-1">
                                                <div v-for="message in validationErrors?.password">
                                                    {{ message }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group password-confirmation">
                                            <label for="password-confirm">비밀번호 확인</label>
                                            <input autocomplete="one-time-code" v-model="registerForm.password_confirmation" id="password_confirmation" type="password" class="form-control" placeholder="비밀번호를 다시 입력해주세요">
                                            <div class="text-danger mt-1">
                                                <div v-for="message in validationErrors?.password_confirmation">
                                                    {{ message }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <div class="form-group dealer-check">
                                                <label for="dealer">혹시 딜러이신가요? </label>
                                                <div class="check_box">
                                                    <input type="checkbox" id="ch2" v-model="registerForm.dealer" class="form-control">
                                                    <label for="ch2">네,딜러에요!</label>
                                                </div>
                                            </div>
                                            <p class="text-secondary opacity-50">딜러라면 추가 정보 입력이 필요해요</p>
                                            <a href="your-link.html" class="icon-link mt-5 mb-3">
                                                <img src="../../../img/Icon-file.png" class="ms-2" alt="회원약관 및 개인정보 처리방침">위카모빌리티 회원약관 및 개인정보처리 방침
                                            </a>
                                            <!-- 딜러 체크 -->
                                            <transition name="slide-fade">
                                                <div v-if="registerForm.dealer" class="hidden-content mt-4">
                                                    <div class="form-group">
                                                        <label for="dealerPhoto">사진 (본인 확인용)</label>
                                                        <img id="imagePreview" style="max-width: 50%; display: none;">
                                                        <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUpload">
                                                            파일 첨부
                                                        </button>
                                                        <input type="file" @change="handleFileUpload" ref="fileInputRef" style="display:none" id="file_user_photo">
                                                        <div class="text-start text-secondary opacity-50" v-if="registerForm.file_user_photo_name">사진 파일 : {{ registerForm.file_user_photo_name }}</div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dealerName">이름</label>
                                                        <input type="text" id="dealerName" v-model="registerForm.dealerName" placeholder="홍길동">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dealerContact">연락처</label>
                                                        <input type="tel" id="dealerContact" v-model="registerForm.dealerContact" @input="checkContact" placeholder="'-'없이 숫자만 입력">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dealerBirthDate">생년월일</label>
                                                        <input type="date" id="dealerBirthDate" v-model="registerForm.dealerBirthDate" @input="checkBirthDate" placeholder="1990-12-30">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dealer">소속상사</label>
                                                        <input type="text" id="dealerName" v-model="registerForm.dealerCompany" placeholder="상사명(상사 정식 명칭)">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dealerName">소속상사 직책</label>
                                                        <input type="text" id="dealerName" v-model="registerForm.dealerCompanyDuty" placeholder="사원">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dealeradress">소속상사 주소</label>
                                                        <input type="text" @click="editPostCode('daumPostcodeInput')" class="input-dis" v-model="registerForm.dealerCompanyPost" placeholder="우편번호" readonly>
                                                        <button type="button" class="search-btn" @click="editPostCode('daumPostcodeInput')">검색</button>
                                                        <div id="daumPostcodeInput" style="display: none; border: 1px solid; width: 100%; height: 466px; margin: 5px 0px; position: relative">
                                                            <img src="//t1.daumcdn.net/postcode/resource/images/close.png" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" @click="closePostcode('daumPostcodeInput')">
                                                        </div>
                                                        <div>
                                                            <input type="text" v-model="registerForm.dealercompany_addr1" placeholder="주소" class="input-dis" readonly>
                                                        </div>
                                                        
                                                        <input type="text" v-model="registerForm.dealercompany_addr2" placeholder="상세주소">
                                                        <div class="password-error" v-if="nameError">이름을 정확히 입력해 주세요.</div>
                                                    </div>
                                                    <div class="form-group">
                                                        <p>추가 서류가 필요해요</p>
                                                        <li>사업자등록증</li>
                                                        <li>매도용인감정보</li>
                                                        <li>매매업체 대표증 or 종사원증</li>
                                                        <div class="d-flex justify-content-flex-end">
                                                            <a href="#" class="seal-info text-secondary opacity-50 my-2 float-end" alt="인감정보양식 다운로드 링크">인감정보양식 다운로드</a>
                                                        </div>
                                                        <p class="mt-4">사업자 등록증</p>
                                                        <input type="file" @change="handleFileUploadBiz" ref="fileInputRefBiz" style="display:none">
                                                        <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadBiz">
                                                            파일 첨부
                                                        </button>
                                                        <div class="text-start mb-3 text-secondary opacity-50" v-if="registerForm.file_user_biz_name">사업자 등록증 : {{ registerForm.file_user_biz_name }}</div>
                                                        <p>매도용인감정보</p>
                                                        <input type="file" @change="handleFileUploadSign" ref="fileInputRefSign" style="display:none">
                                                        <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadSign">
                                                            파일 첨부
                                                        </button>
                                                        <div class="text-start mb-3 text-secondary opacity-50" v-if="registerForm.file_user_sign_name">매도용인감정보 : {{ registerForm.file_user_sign_name }}</div>
                                                        <p>매매업체 대표증 or 종사원증</p>
                                                        <input type="file" @change="handleFileUploadCert" ref="fileInputRefCert" style="display:none">
                                                        <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadCert">
                                                            파일 첨부
                                                        </button>
                                                        <div class="text-start mb-5 text-secondary opacity-50" v-if="registerForm.file_user_cert_name">매매업체 대표증 / 종사원증 : {{ registerForm.file_user_cert_name }}</div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="deliveryAdress">인수차량 도착지 주소</label>
                                                        <input type="text" class="input-dis" v-model="registerForm.dealerReceivePost" placeholder="우편번호" readonly>
                                                        <button type="button" class="search-btn" @click="editPostCodeReceive('daumPostcodeDealerReceiveInput')">검색</button>
                                                        <div id="daumPostcodeDealerReceiveInput" style="display: none; border: 1px solid; width: 100%; height: 466px; margin: 5px 0px; position: relative">
                                                            <img src="//t1.daumcdn.net/postcode/resource/images/close.png" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" @click="closePostcode('daumPostcodeDealerReceiveInput')">
                                                        </div>
                                                        <div>
                                                            <input  type="text" class="input-dis" v-model="registerForm.dealerReceiveAddr1" placeholder="주소" readonly>
                                                        </div>
                                                        
                                                        <input type="text" v-model="registerForm.dealerReceiveAddr2" placeholder="상세주소">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="introduce">소개</label>
                                                        <textarea v-model="registerForm.introduce" rows="4" class="form-control no-resize mt-2" placeholder="소개를 입력해주세요."></textarea>
                                                    </div>
                                                </div>
                                            </transition>
                                        </div>
                                        <!-- Buttons -->
                                        <div class="flex items-center justify-end mt-4">
                                            <button type="submit" class="btn btn-primary" :class="{ 'opacity-25': processing }" :disabled="processing">
                                                약관 동의 및 회원가입
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {
    ref,
    onMounted
} from 'vue';
import useAuth from '@/composables/auth';
import { cmmn } from '@/hooks/cmmn';
const { openPostcode , closePostcode} = cmmn();
const {
    registerForm,
    validationErrors,
    processing,
    submitRegister
} = useAuth();
const fileInputRefBiz = ref(null);
const fileInputRefSign = ref(null);
const fileInputRefCert = ref(null);
const fileInputRef = ref(null);

function editPostCode(elementName) {
  openPostcode(elementName)
    .then(({ zonecode, address }) => {
        registerForm.dealerCompanyPost = zonecode;
        registerForm.dealercompany_addr1 = address;
    })
}

function editPostCodeReceive(elementName) {
    openPostcode(elementName)
    .then(({ zonecode, address }) => {
        registerForm.dealerReceivePost = zonecode;
        registerForm.dealerReceiveAddr1 = address;
    })
}

function triggerFileUpload() {
    if (fileInputRef.value) {
        fileInputRef.value.click();
    } else {
        console.error("파일을 찾을수 없습니다.");
    }
}

function handleFileUpload(event) {
    const file = event.target.files[0];
    if (file) {
        registerForm.file_user_photo = file;
        registerForm.file_user_photo_name = file.name;
        console.log("File:", file.name);
    }
}

function triggerFileUploadBiz() {
    if (fileInputRefBiz.value) {
        fileInputRefBiz.value.click();
    } else {
        console.error("사업자등록증 파일을 찾을 수 없습니다.");
    }
}

function handleFileUploadBiz(event) {
    const file = event.target.files[0];
    if (file) {
        registerForm.file_user_biz = file;
        registerForm.file_user_biz_name = file.name;
        console.log("Business registration file:", file.name);
    }
}

function triggerFileUploadSign() {
    if (fileInputRefSign.value) {
        fileInputRefSign.value.click();
    } else {
        console.error("인감 정보 파일을 찾을 수 없습니다.");
    }
}

function handleFileUploadSign(event) {
    const file = event.target.files[0];
    if (file) {
        registerForm.file_user_sign = file;
        registerForm.file_user_sign_name = file.name;
        console.log("Signature file:", file.name);
    }
}

function triggerFileUploadCert() {
    if (fileInputRefCert.value) {
        fileInputRefCert.value.click();
    } else {
        console.error("대표증 또는 종사원증 파일을 찾을 수 없습니다.");
    }
}

function handleFileUploadCert(event) {
    const file = event.target.files[0];
    if (file) {
        registerForm.file_user_cert = file;
        registerForm.file_user_cert_name = file.name;
        console.log("Certification file:", file.name);
    }
}

/** 
function openPostcode() {
    new daum.Postcode({
        oncomplete: function(data) {
            registerForm.dealerCompanyPost = data.zonecode;
            registerForm.dealercompany_addr1 = data.address;
            registerForm.dealercompany_addr2 = '';
        }
    }).open();
}*/



onMounted(() => {

    const firstImage = document.querySelector('.animated-image-1');
    const secondImage = document.querySelector('.animated-image-2');

    const startFirstAnimation = () => {
        firstImage.style.display = 'block';
    };

    const startSecondAnimation = () => {
        secondImage.style.display = 'block';
    };

    firstImage.addEventListener('animationend', () => {
        firstImage.style.display = 'none';
        startSecondAnimation(); 
    });

    secondImage.addEventListener('animationend', () => {
        secondImage.style.display = 'none';
        startFirstAnimation();
    });

    startFirstAnimation();
});
</script>

<style scoped>
body {
    overflow-x: hidden;
    overflow-y: auto;
}

.image-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    overflow: hidden;
    z-index: -1; /* 다른 콘텐츠 뒤에 배치 */
}

.animated-image-1,
.animated-image-2 {
    display: block;
    position: absolute;
}

.animated-image-1 {
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    animation: moveImage 5s linear forwards;
}

.animated-image-2 {
    top: 50%;
    right: -20%;
    transform: translate(-100%, -50%);
    display: none;
}
.login-card::before {
    display: none;
}
.seal-info {
    text-decoration: underline !important;
}

.password-confirmation {
    position: relative;
}

.error-icon {
    position: absolute;
    right: 10px;
    top: 10px;
    color: #D93025;
    font-size: 20px;
}

.password-error {
    color: #D93025;
    text-align: right;
    margin-top: 5px;
}
.main-content {
    flex: 1;
}

.navbar>.container-fluid {
    display: flex;
    flex-wrap: inherit;
    align-items: center;
    justify-content: space-between;
}

.logo {
    height: 50px;
}

.navbar-light .navbar-toggler {
    color: rgba(0, 0, 0, .55);
    border-color: rgba(0, 0, 0, 0);
}

.card .form-control,
.card .btn {
    width: 100%;
    box-sizing: border-box;
}

.container-fluid {
    padding-left: 0;
    padding-right: 0;
}

.main-content {
    padding-top: 56px;
}

.d-grid {
    margin-left: -16px;
    margin-right: -16px;
}

.card {
    border-radius: 20px;
    padding: 16px;
    max-width: 410px;
}

.sidebar {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    width: 250px;
    background: #fff;
    box-shadow: -2px 0 5px rgba(0, 0, 0, .3);
    padding: 20px;
    transform: translateX(100%);
    transition: transform .3s ease-in-out;
}

.sidebar-enter-active,
.sidebar-leave-active {
    transition: transform .3s ease-in-out;
}

.sidebar-enter,
.sidebar-leave-to {
    transform: translateX(100%);
}

.main-content {
    transition: margin-right .3s ease-in-out;
}

.main-content[style] {
    margin-right: 250px;
}

.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1;
}

.register-content {
    display: flex;
    justify-content: space-between;
    margin-top: 2rem;
}

.video-container {
    position: absolute;
    left: 0;
    bottom: 0;
}

.card-section {
    flex: 1;
    max-width: 390px;
}

/*******************************
   text-style
********************************/
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

/*******************************
   regist form
********************************/
.form-header h1 {
    color: #333;
    text-align: center;
    margin-bottom: 30px;
}

.form-body {
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 14px;
    color: #333;
}

.form-group input[type=email],
.form-group input[type=password],
.form-group input[type=text],
.form-group input[type=tel],
.form-group input[type=date] {
    width: 100%;
    padding: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-top: 10px;
}

/* error-guide style */
.password-error {
    color: #D93025;
    text-align: right;
    margin-top: 5px;
}

.dealer-check {
    margin-top: 50px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #F5F5F6;
    border-radius: 30px;
    padding: 10px;
}

.no-resize {
    resize: none;
}

.dealer-check input[type=checkbox] {
    margin-right: 10px;
}

.dealer-check label {
    display: flex;
    align-items: center;
    font-size: 14px;
    color: #333;
}

.dealer-check label span {
    color: #999;
    margin-left: 5px;
}

.submit-btn {
    width: 100%;
    padding: 15px;
    background-color: #E60023;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.submit-btn:hover {
    background-color: #C5001D;
}

.password-input-container {
    position: relative;
    display: flex;
    align-items: center;
}

.form-group input,
.form-group input {
    border-radius: 8px !important;
}

.hidden-content label,
.hidden-content p,
.hidden-content li {
    text-align: start;
}

.check_box input[type='checkbox']+label {
    display: inline-block;
    color: #acacac;
}

.check_box input[type='checkbox']:checked+label {
    display: inline-block;
    font-weight: 600;
    color: #000;
}

.check_box input[type='checkbox']+label::after {
    display: inline-block;
    content: "";
    width: 20px;
    height: 20px;
    background: url('/resources/img/none_checkbox.png') no-repeat;
    background-size: 20px;
    vertical-align: top;
    margin: 1px 9px 0px 9px;

}

.check_box input[type='checkbox']:checked+label::after {
    width: 20px;
    height: 20px;
    background: url('/resources/img/checkbox.png') no-repeat;
    background-size: 20px;
}

.check_box input[type='checkbox'] {
    position: absolute;
    overflow: hidden;
    width: 1px;
    height: 1px;
    margin: -1px;
    opacity: 0;

}

div.main {
    position: relative;
    padding: 20px;
}

div.main input {
    width: 300px;
    height: 30px;
    background-color: black;
    border: 0;
    color: white;
    text-indent: 10px;
}

div.main i {
    position: absolute;
    left: 75%;
    top: 27px;
    color: orange;
}

.btn-fileupload {
    display: block;
    font-weight: bold;
    width: 100%;
    margin-top: 12px;
    padding: 0.7rem 0.75rem;
    font-size: 0.875rem;
    color: #abadb6;
    background-color: #F5F5F6;
    border: 1px solid #F5F5F6;
    border-radius: 0.3rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
        border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.btn-fileupload:hover {
    background-color: #e7e7e7;
    border: 1px solid #ced4da;
}

.slide-fade-enter-active,
.slide-fade-leave-active {
    transition: all 0.5s ease;
}

.slide-fade-enter,
.slide-fade-leave-to {
    opacity: 0;
}

@keyframes formopen {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

.slide-fade-enter-active {
    animation: formopen 0.3s ease forwards;
}

.animated-image-1 {
    position: absolute;
    top: 0;
    animation: moveImage 5s linear forwards;
    z-index: -1;
}

.animated-image-2 {
    position: absolute;
    top: 50%;
    right: -20%;
    animation: moveSecondImage 5s linear forwards;
    z-index: -1;
}

@keyframes moveImage {
    0% {
        top: 0;
        left: 50%;
        transform: translateX(-50%);
    }

    100% {
        top: 50%;
        left: 120%;
        transform: translate(-100%, -50%);
    }
}

@keyframes moveSecondImage {
    0% {
        top: 50%;
        right: -20%;
        transform: translate(-100%, -50%);
    }

    100% {
        top: 100%;
        right: 50%;
        transform: translateX(-50%);
    }
}

.css-ifyyt1 {
    display: grid;
    padding: 18vh 17px 5vh;
    color: rgb(39, 46, 64);
    align-content: space-evenly;
}

.css-kzs0t {
    transition: opacity 0.8s ease-in-out 0s, transform 0.8s ease-in-out 0s;
    opacity: 1;
    transform: translateY(0px);
}

.css-91307t {
    color: rgb(57, 110, 255);
}

.font-sub-title {
    font-size: 1.25rem;
    line-height: 1.75rem;
}

.font-title {
    font-size: 2.3rem;
    line-height: 3.3rem;
}

.hidden {
    opacity: 0;
    transform: translateY(20px);
}

@media (max-width: 768px) {
    .css-ifyyt1 {
        display: none;
    }
    .video_type01{
        display: none;
    }
    .register-content{
        justify-content: center;
    }
}

@media (max-width: 991px) {
    .font-title {
        font-size: 1.8rem;
        line-height: 2rem;
   

    }
}

.ellipsis-animation::after {
content: '...';
animation: ellipsis 1.5s steps(4, end) infinite;
}

@keyframes ellipsis {
0%{
    content: '';
}
25% {
    content: '.';
}
50% {
    content: '..';
}
100% {
    content: '...';
}
}

@media (max-width: 1250px)

{
.animated-image-1{
    display: none !important;
}
.animated-image-2{
    display: none !important;
}
}

@media (max-width: 991px)

{
    .break-line {
                display: block;
                width: 100%;
            }
}

.search-btn {
    right: 22px !important;
    transform: translateY(50%) !important;
}
</style>
