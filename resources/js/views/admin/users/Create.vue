<template>
    <h4><span class="admin-icon admin-icon-menu"></span>회원관리</h4>
        <div class="container my-5">
            <div class="col-md-10">
                <div class="card border-0 shadow-none">
                    <div class="card-body">
                        <form @submit.prevent="submitForm">
                            <div class="mb-3">
                                <label for="user-title" class="form-label"
                                    >이름</label
                                >
                                <input
                                    v-model = "user.name"
                                    type="text"
                                    class="form-control"
                                    autocomplete="one-time-code"
                                    required
                                />
                                <div class="text-danger mt-1">
                                    <div v-for="message in validationErrors?.name">
                                        {{ message }}
                                    </div>
                                </div>
    
                            </div>
                            <div class="mb-3">
                                <label for="user-title" class="form-label"
                                    >email</label
                                >
                                <input
                                    v-model = "user.email"
                                    type="text"
                                    class="form-control"
                                    autocomplete="one-time-code"
                                    required
                                />
                                <div class="text-danger mt-1">
                                    <div v-for="message in validationErrors?.email">
                                        {{ message }}
                                    </div>
                                </div>
    
                            </div>
                            <div class="mb-3">
                                <label for="user-title" class="form-label"
                                    >비밀번호</label
                                >
                                <input
                                    v-model = "user.password"
                                    type="password"
                                    class="form-control"
                                    autocomplete="one-time-code"
                                    required
                                />
                                <div class="text-danger mt-1">
                                    <div v-for="message in validationErrors?.password">
                                        {{ message }}
                                    </div>
                                </div>
    
                            </div>
                            <div class="mb-3">
                                <label for="user-title" class="form-label"
                                    >비밀번호 확인</label
                                >
                                <input
                                    v-model = "user.password_confirmation"
                                    type="password"
                                    class="form-control"
                                    autocomplete="one-time-code"
                                    required
                                />
                                <div class="text-danger mt-1">
                                    <div v-for="message in validationErrors?.password">
                                        {{ message }}
                                    </div>
                                </div>
    
                            </div>
                            <div class="mb-3">
                                <label for="user-title" class="form-label"
                                    >전화번호</label
                                >
                                <input
                                    v-model = "user.phone"
                                    type="text"
                                    class="form-control"
                                    required
                                />
    
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">회원</label>
                                <select class="form-select" :v-model="user.role" @change="changeUser($event)" id="roleSelect">
                                    <option value="user">일반</option>
                                    <option value="dealer">딜러</option>
                                    <option value="admin">관리자</option>
                                </select>
                            </div>
                            <div v-if="userRoles=='dealer'">
                                <div class="mb-3">
                                    <label for="user-title" class="form-label"
                                        >이름</label
                                    >
                                    <input
                                        v-model = "dealer.name"
                                        type="text"
                                        class="form-control"
                                        required
                                    />
                                </div>
                                <div class="mb-3">
                                    <label for="user-title" class="form-label"
                                        >전화번호</label
                                    >
                                    <input
                                        v-model = "dealer.phone"
                                        type="text"
                                        class="form-control"
                                        required
                                    />
                                </div>
                                <div class="mb-3">
                                    <label for="user-title" class="form-label"
                                        >생년월일</label
                                    >
                                    <input
                                        v-model = "dealer.birthday"
                                        type="text"
                                        class="form-control"
                                        required
                                    />
                                </div>
                                <div class="mb-3">
                                    <label for="user-title" class="form-label"
                                        >소속상사</label
                                    >
                                    <input
                                        v-model = "dealer.company"
                                        type="text"
                                        class="form-control"
                                        required
                                    />
                                </div>
                                <div class="mb-3">
                                    <label for="user-title" class="form-label"
                                        >직급</label
                                    >
                                    <input
                                        v-model = "dealer.company_duty"
                                        type="text"
                                        class="form-control"
                                        required
                                    />
                                </div>
                                <div class="form-group">
                                    <label for="user-title" class="form-label">소속상사 주소</label>
                                    
                                    <input
                                        type="text"
                                        v-model="dealer.company_post"
                                        class="input-dis form-control"
                                        placeholder="우편번호"
                                        readonly
                                        />
                                        <button type="button" class="search-btn" @click="editPostCode('daumPostcodeInput')">검색</button>
                                        <div id="daumPostcodeInput" style="display: none; border: 1px solid; width: 100%; height: 466px; margin: 5px 0px; position: relative">
                                            <img src="//t1.daumcdn.net/postcode/resource/images/close.png" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" @click="closePostcode('daumPostcodeInput')">
                                        </div>
                                        <input
                                            type="text"
                                            v-model="dealer.company_addr1"
                                            class="input-dis form-control"
                                            placeholder="주소"
                                            readonly
                                        />
                                    <input
                                        v-model="dealer.company_addr2"
                                        type="text"
                                        class="form-control"
                                        placeholder="상세주소"
                                        required
                                    />
                                </div>
                                <div class="form-group">
                                    <label for="user-title" class="form-label">인수차량 도착지 주소</label>
                                    <input
                                        type="text" 
                                        v-model="dealer.receive_post"
                                        class="input-dis"
                                        placeholder="우편번호"
                                        readonly
                                    />
                                    <button type="button" class="search-btn2" @click="editPostCodeReceive('daumPostcodeDealerReceiveInput')">검색</button>
                                    <div id="daumPostcodeDealerReceiveInput" style="display: none; border: 1px solid; width: 100%; height: 466px; margin: 5px 0px; position: relative">
                                        <img src="//t1.daumcdn.net/postcode/resource/images/close.png" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" @click="closePostcode('daumPostcodeDealerReceiveInput')">
                                    </div>
                                    <div>
                                        <input
                                            type="text"
                                            v-model="dealer.receive_addr1"
                                            class="input-dis"
                                            placeholder="주소"
                                            readonly
                                        />
                                
                                    </div>
                                    <input
                                        v-model="dealer.receive_addr2"
                                        type="text"
                                        class="form-control"
                                        placeholder="상세주소"
                                        required
                                    />
                                </div>
    
                                <div class="mb-3">
                                    <label for="user-title" class="form-label"
                                        >소개</label
                                    >
                                    <textarea
                                        v-model = "dealer.introduce"
                                        type="text"
                                        class="custom-textarea mt-2"
                                    ></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="user-title" class="form-label"
                                        >사진(본인 확인용)</label
                                    >
                                    <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUpload">
                                        파일 첨부
                                    </button>
                                    <input type="file" @change="handleFileUpload" ref="fileInputRef" style="display:none" id="file_user_photo">
                                    <div class="text-start text-secondary opacity-50" v-if="dealer.file_user_photo_name">사진 파일 : {{ dealer.file_user_photo_name }}</div>
                                </div>
                                <div class="mb-3">
                                    <label for="user-title" class="form-label"
                                        >사업자 등록증</label
                                    >
                                    <input type="file" @change="handleFileUploadBiz" ref="fileInputRefBiz" style="display:none">
                                    <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadBiz">
                                        파일 첨부
                                    </button>
                                    <div class="text-start mb-3 text-secondary opacity-50" v-if="dealer.file_user_biz_name">사업자 등록증 : {{ dealer.file_user_biz_name }}</div>
                                </div>
                                <div class="mb-3">
                                    <label for="user-title" class="form-label"
                                        >매도용인감정보</label
                                    >
                                    <input type="file" @change="handleFileUploadSign" ref="fileInputRefSign" style="display:none">
                                    <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadSign">
                                        파일 첨부
                                    </button>
                                    <div class="text-start mb-3 text-secondary opacity-50" v-if="dealer.file_user_sign_name">매도용인감정보 : {{ dealer.file_user_sign_name }}</div>
                                </div>
                                <div class="mb-3">
                                    <label for="user-title" class="form-label"
                                        >매매업체 대표증 or 종사원증</label
                                    >
                                    <input type="file" @change="handleFileUploadCert" ref="fileInputRefCert" style="display:none">
                                    <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadCert">
                                        파일 첨부
                                    </button>
                                    <div class="text-start mb-5 text-secondary opacity-50" v-if="dealer.file_user_cert_name">매매업체 대표증 / 종사원증 : {{ dealer.file_user_cert_name }}</div>
                                </div>

                            </div>
                            <!-- Buttons -->
    
                            
                            <div class="mt-4">
                                <button
                                    :disabled="isLoading"
                                    class="w-100 btn btn-primary"
                                >
                                    <div v-show="isLoading" class=""></div>
                                    <span v-if="isLoading">Processing...</span>
                                    <span v-else>등록</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <script setup>
    import { onMounted, reactive, ref } from "vue";
    import { useRoute } from "vue-router";
    import useUsers from "@/composables/users";
    import { required, min } from "@/validation/rules";
    import { cmmn } from '@/hooks/cmmn';
    
    const route = useRoute();
    const { openPostcode , closePostcode} = cmmn();
    const userRoles = ref("");

    const {
        adminStoreUser,
        validationErrors,
        isLoading,
    } = useUsers();
    
    const fileInputRefBiz = ref(null);
    const fileInputRefSign = ref(null);
    const fileInputRefCert = ref(null);
    const fileInputRef = ref(null);

    
    // Define a validation schema
    const schema = {
        name: { required },
        password: { required },
        password_confirmation: { required },
        phone: { required },
        role: { required },
    };
    
    const user = reactive({
        name:'',
        password:'',
        password_confirmation:'',
        phone:'',
        email:'',
        role:"user"
    });
    const dealer = reactive({
        name:'',
        phone:'',
        birthday: '',
        company_duty: '',
        company:'',
        company_post:'',
        company_addr1:'',
        company_addr2:'',
        introduce:'',
        receive_post:'',
        receive_addr1:'',
        receive_addr2:'',
        file_user_photo:"",
        file_user_photo_name:"",
        file_user_biz:"",
        file_user_biz_name:"",
        file_user_sign:"",
        file_user_sign_name:"",
        file_user_cert:"",
        file_user_cert_name:"",
    })
    
    function submitForm() {
        adminStoreUser(user,dealer);
    }
    
    function editPostCode(elementName) {
      openPostcode(elementName)
        .then(({ zonecode, address }) => {
            dealer.company_post = zonecode;
            dealer.company_addr1 = address;
        })
    }
    function editPostCodeReceive(elementName) {
        openPostcode(elementName)
        .then(({ zonecode, address }) => {
            dealer.receive_post = zonecode;
            dealer.receive_addr1 = address;
        })
    }
    
    function changeUser(event) {
        userRoles.value = event.target.value;
        user.role = event.target.value;
    }

    function triggerFileUpload() {
    if (fileInputRef.value) {
        fileInputRef.value.click();
    } else {
        console.error("파일을 찾을수 없습니다.");
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

function handleFileUpload(event) {
    const file = event.target.files[0];
    if (file) {
        dealer.file_user_photo = file;
        dealer.file_user_photo_name = file.name;
        console.log("File:", file.name);
    }
}

function handleFileUploadBiz(event) {
    const file = event.target.files[0];
    if (file) {
        dealer.file_user_biz = file;
        dealer.file_user_biz_name = file.name;
        console.log("Business registration file:", file.name);
    }
}

function handleFileUploadSign(event) {
    const file = event.target.files[0];
    if (file) {
        dealer.file_user_sign = file;
        dealer.file_user_sign_name = file.name;
        console.log("Signature file:", file.name);
    }
}

function handleFileUploadCert(event) {
    const file = event.target.files[0];
    if (file) {
        dealer.file_user_cert = file;
        dealer.file_user_cert_name = file.name;
        console.log("Certification file:", file.name);
    }
}

    </script>
    <style>
    h4 {
        position: relative;
        padding-bottom: 15px;
    }
    
    h4::after {
        content: "";
        display: block;
        width: 100%;
        height: 2px; 
        background-color: #f7f8fb;
        position: absolute;
        bottom: 0;
        left: 0;
    }
.search-btn2 {
    cursor: pointer;
    position: absolute;
    right: 5px;
    transform: translateY(55%);
    height: 40px;
    width: 40px;
    border: none;
    background-color: transparent;
    background-image: url('../../../../img/search.png');
    background-repeat: no-repeat;
    background-position: center;
    background-size: 20px 20px;
    font-size: 0;
}
    </style>