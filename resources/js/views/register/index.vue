<template>
    <!-- Page content -->
    <div class="container">
        <div class="register-content">
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
                        <p class="mb-4 fw-bold">회원 정보를 입력해주세요13</p>
                            </div>
                            <div class="form-body">
                                <form @submit.prevent="submitRegister">
                                    <div class="form-group">
                                        <label for="email">이메일</label>
                                        <input type="email" id="email" v-model="registerForm.email" @input="checkEmail" :class="{'error-border': emailError}" placeholder="example@email.com">
                                        <div class="text-danger mt-1">
                                        <div v-for="message in validationErrors?.email">
                                            {{ message }}
                                        </div>
                                    </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">비밀번호</label>
                                        <input type="password" id="password" v-model="registerForm.password" @input="checkPassword" :class="{'error-border': passwordinputError}" placeholder="6~8자리 숫자,영어,특수문자 혼합">
                                        <div class="text-left password-error" v-if="passwordinputError">비밀번호는 6~8자리의 숫자, 영문자, 특수문자를 혼합해야 합니다.</div>
                                    </div>
                                    <div class="form-group password-confirmation">
                                        <label for="password-confirm">비밀번호 확인</label>
                                        <input type="password" id="password-confirm" v-model="registerForm.password_confirmation" @input="checkPassword" :class="{'error-border': passwordError, 'correct-border': passwordCorrect}" placeholder="비밀번호를 다시 입력해주세요">
                                        <i class="fas fa-times error-icon" v-if="passwordError"></i>
                                        <i class="fas fa-check correct-icon" v-if="passwordCorrect"></i>
                                        <div class="text-left password-error" v-if="passwordError">비밀번호를 확인해주세요</div>
                                        <div class="text-left password-correct" v-if="passwordCorrect">비밀번호를 확인</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="form-group dealer-check">
                                            <label for="dealer">혹시 딜러이신가요? </label>
                                            <div class="check_box">
                                                <input type="checkbox" id="ch2" v-model="registerForm.dealer" class="form-control">
                                                <label for="ch2">네,딜러에요!</label>
                                            </div>
                                        </div>
                                        <p class="text-muted">딜러라면 추가 정보 입력이 필요해요</p>
                                         <a href="your-link.html" class="icon-link mt-5 mb-3">
                                            <img src="../../../img/Icon-file.png" class="ms-2" alt="회원약관 및 개인정보 처리방침">위카모빌리티 회원약관 및 개인정보처리 방침
                                        </a>

                                        <!-- 딜러 체크 -->
                                        <transition name="slide-fade">
                                            <div v-if="isDealer" class="hidden-content mt-4">
                                                <div class="form-group">
                                                    <label for="dealerPhoto">사진 (본인 확인용)</label>
                                                    <button type="button" class="btn btn-fileupload" @click="openUploadPage">
                                                        <img src="../../../img/Icon-upload.png" class="me-2 " alt="설명">파일 첨부
                                                    </button>
                                                    <div class="password-error" v-if="photoError">사진을 업로드해 주세요.</div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="dealerName">이름</label>
                                                    <input type="text" id="dealerName" v-model="dealerName" @input="checkName" :class="{'nameError-border': nameError}" placeholder="홍길동">
                                                    <div class="password-error" v-if="nameError">이름을 정확히 입력해 주세요.</div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="dealerContact">연락처</label>
                                                    <input type="tel" id="dealerContact" v-model="dealerContact" @input="checkContact" :class="{'contactError-border': contactError}" placeholder="'-'없이 숫자만 입력">
                                                    <div class="password-error" v-if="contactError">연락처를 정확히 입력해 주세요.</div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="dealerBirthDate">생년월일</label>
                                                    <input type="date" id="dealerBirthDate" v-model="dealerBirthDate" @input="checkBirthDate" :class="{'birthDateError-border': birthDateError}" placeholder="1990-12-30">
                                                    <div class="password-error" v-if="birthDateError">생년월일을 정확히 입력해 주세요.</div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="dealer">소속상사</label>
                                                    <input type="text" id="dealerName" v-model="dealerName" @input="checkName" :class="{'nameError-border': nameError}" placeholder="상사명(상사 정식 명칭)">
                                                    <div class="password-error" v-if="nameError">이름을 정확히 입력해 주세요.</div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="dealerName">소속상사 직책</label>
                                                    <input type="text" id="dealerName" v-model="dealerName" @input="checkName" :class="{'nameError-border': nameError}" placeholder="사원">
                                                    <div class="password-error" v-if="nameError">이름을 정확히 입력해 주세요.</div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="dealeradress">소속상사 주소</label>
                                                    <input type="text" id="dealeradress" placeholder="주소" class="searchadress">
                                                    <input type="text" id="dealeradress" placeholder="상세주소">
                                                    <div class="password-error" v-if="nameError">이름을 정확히 입력해 주세요.</div>
                                                </div>
                                                <div class="form-group">
                                                    <p>추가 서류가 필요해요</p>
                                                    <li>사업자등록증</li>
                                                    <li>매도용인감정보</li>
                                                    <li>매매업체 대표증 or 종사원증</li>
                                                    <a href="#" class="seal-info text-muted my-2 float-end" alt="인감정보양식 다운로드 링크">인감정보양식 다운로드</a>
                                                    <button type="button" class="btn btn-fileupload" @click="openUploadPage">
                                                        <img src="../../../img/Icon-upload.png" class="me-2 " alt="설명">파일 첨부
                                                    </button>
                                                </div>
                                                <div class="form-group">
                                                    <label for="deliveryAdress">인수차량 도착지 주소</label>
                                                    <input type="text" class="{'nameError-border': nameError}" placeholder="주소">
                                                    <input type="text" class="{'nameError-border': nameError}" placeholder="상세주소">
                                                </div>
                                                <div class="form-group">
                                                    <label for="introduce">소개</label>
                                                    <textarea id="description" v-model="description" rows="4" class="form-control no-resize mt-2" placeholder="소개를 입력해주세요."></textarea>
                                                    <div class="password-error" v-if="nameError">이름을 정확히 입력해 주세요.</div>
                                                </div>
                                            </div>
                                        </transition>
                                    </div>
                             <!-- Buttons -->
                                    <div class="flex items-center justify-end mt-4">
                                        <button class="btn btn-primary" :class="{ 'opacity-25': processing }" :disabled="processing">
                                            {{ $t('약관 동의 및 회원가입') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="overlay" v-if="isSidebarOpen" @click="isSidebarOpen = false"></div>
            </div>
        </div>
    </div>
</template>

<script setup>

import useAuth from '@/composables/auth'

const { registerForm, validationErrors, processing, submitRegister } = useAuth();

</script>

<script>
    export default {
        data() {
            return {
                userEmail: '', //공통 : 이메일
                userPassword: '', //공통 : 비밀번호
                confirmPassword: '', //공통 : 비밀번호 확인
                dealerName: '', //딜러 : 이름    //사진첨부는일단 보류?
                dealerContact: '', //딜러 : 연락처
                dealerBirthDate: '', //딜러 : 생년월일 
                isDealer: false,
                isSidebarOpen: false,
                passwordCorrect: false,
                passwordError: false,
                emailError: false,
                passwordinputError: false,
                nameError: false,
                contactError: false,
                birthDateError: false,
                photoError: false,
            };
        },
        methods: {
            // 비밀번호 유효성검사.
            checkPassword() {
                const passwordRegex = /^(?=.*\d)(?=.*[a-zA-Z])(?=.*\W).{6,8}$/;

                if (this.userPassword) {
                    if (!passwordRegex.test(this.userPassword)) {
                        this.passwordinputError = true;
                        return;
                    }
                }
                if (this.userPassword && this.confirmPassword) {
                    if (this.userPassword === this.confirmPassword) {
                        this.passwordCorrect = true;
                    } else {
                        this.passwordError = true;
                    }
                } else if (!this.confirmPassword) {
                    this.passwordError = true;
                }
            },
            // 이메일 유효성 검사.
            checkEmail() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(this.userEmail)) {
                    this.emailError = true;
                } else {
                    this.emailError = false;
                }
            },
            // 딜러 유효성 검사
            checkName() {
                const nameRegex = /^[가-힣a-zA-Z]+$/;
                if (!this.dealerName.trim() || !nameRegex.test(this.dealerName)) {
                    this.nameError = true;
                } else {
                    this.nameError = false;
                }
            },

            checkContact() {
                const phoneRegex = /^\d{10,11}$/;
                if (!phoneRegex.test(this.dealerContact)) {
                    this.contactError = true;
                } else {
                    this.contactError = false;
                }
            },

            checkBirthDate() {
                const birthDateRegex = /^\d{4}-\d{2}-\d{2}$/;
                if (!birthDateRegex.test(this.dealerBirthDate)) {
                    this.birthDateError = true;
                    return;
                } else {
                    this.birthDateError = false;
                }

                const birthDate = new Date(this.dealerBirthDate);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const m = today.getMonth() - birthDate.getMonth();

                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                if (age < 18) {
                    this.birthDateError = true;
                } else {
                    this.birthDateError = false;
                }

            },
            //회원가입 데이터 폼 제출
            submitLoginForm() {
                this.checkPassword();
                this.checkEmail();

                if (this.isDealer) {
                    this.checkName();
                    this.checkContact();
                    this.checkBirthDate();
                    if (this.nameError || this.contactError || this.birthDateError) {
                        console.log("폼 제출에 문제가 있습니다: 유효성 검사 실패");
                        return;
                    }
                }
                if (this.emailError || this.passwordinputError || this.passwordError) {
                    console.log("폼 제출에 문제가있서요 : 유효성 검사 실패");
                    return;
                }


                let saveData = {
                    userEmail: this.userEmail,
                    userPassword: this.userPassword,
                    userRole: this.isDealer ? '딜러' : '일반 사용자',
                };

                if (this.isDealer) {
                    saveData.dealerDetails = {
                        name: this.dealerName,
                        contact: this.dealerContact,
                        birthDate: this.dealerBirthDate,
                    };
                }
                console.log("데이터:", saveData);

                this.$router.push('/home');
            },
        },
        mounted() {
            document.body.style.backgroundColor = "#f7f8fb";
        },

        beforeRouteLeave(to, from, next) {
            document.body.style.backgroundColor = "#ffffff";
            next();
        },
        created() {
            console.log('컴포넌트 생성');
        },
    };
</script>

<style scoped>
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


    .page-container {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
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
        justify-content: flex-end;
        gap: 20px;
        margin-top: 60px;
        padding: 10px;
    }

    .video-container {
        position: fixed;
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

    .text-muted {
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

    .error-icon {
        position: absolute;
        right: 18px;
        color: #D93025;
        font-size: 20px;
        cursor: pointer;
        top: 44px;
        margin-top: -2px;
    }

    .error-border {
        border: 1px solid #c40000 !important;
    }

    .emailError-border {
        border: 1px solid #c40000 !important;
    }

    .nameError-border {
        border: 1px solid #c40000 !important;
    }

    .contactError-border {
        border: 1px solid #c40000 !important;
    }

    .birthDateError-border {
        border: 1px solid #c40000 !important;
    }

    .emailError {
        color: #c40000;
        margin-top: 5px;
    }

    .passwordinputError {
        color: #c40000;
        margin-top: 5px;
    }

    .nameError {
        color: #c40000;
        margin-top: 5px;
    }

    .contactError {
        color: #c40000;
        margin-top: 5px;
    }

    .birthDateError {
        color: #c40000;
        margin-top: 5px;
    }

    .password-correct {
        color: #0400ff;
        margin-top: 5px;
    }

    .correct-icon {
        position: absolute;
        right: 18px;
        color: #0400ff;
        font-size: 20px;
        cursor: pointer;
        top: 44px;
        margin-top: -2px;
    }

    .correct-border {
        border: 1px solid #0400ff !important;
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
</style>