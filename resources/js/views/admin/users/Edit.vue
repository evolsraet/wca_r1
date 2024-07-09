<template>
<h4><span class="admin-icon admin-icon-menu"></span>회원관리</h4>
    <div class="row justify-content-center my-5">
        <div class="col-md-10">
            <div class="card border-0 shadow-none">
                <div class="card-body">
                    <form @submit.prevent="submitForm">
                        <div class="mb-3">
                            <label for="user-title" class="form-label"
                                >이름</label
                            >
                            <input
                                v-model = "editForm.name"
                                type="text"
                                class="form-control"
                            />
                        </div>
                        <div class="mb-3">
                            <label for="user-title" class="form-label"
                                >가입일</label
                            >
                            <input
                                v-model = "created_at"
                                type="text"
                                class="input-dis form-control"
                                readonly
                            />
                        </div>
                        <div class="mb-3">
                            <label for="user-title" class="form-label"
                                >최종 수정일</label
                            >
                            <input
                                v-model = "updated_at"
                                type="text"
                                class="input-dis form-control"
                                readonly
                            />
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                v-model = "email"
                                type="email"
                                class="input-dis form-control"
                                readonly
                            />
                            
                        </div>
                        <div class="mb-3">
                            <label for="user-title" class="form-label"
                                >전화번호</label
                            >
                            <input
                                v-model = "editForm.phone"
                                type="text"
                                class="form-control"
                            />

                        </div>

                        <!-- Role 
                        <div class="mb-3">
                            <label for="user-category" class="form-label" >
                                권한
                            </label>
                            <v-select id="roleSelect"
                                v-model="user.role"
                                multiple
                                :options="roleList"
                                :reduce="(role) => role.id"
                                label="name"
                                class="form-control"
                                @click="test"
                            />
                             <div class="text-danger mt-1">
                                {{ errors.role_id }}
                            </div>
                            <div class="text-danger mt-1">
                                <div
                                    v-for="message in validationErrors?.role_id"
                                >
                                    {{ message }}
                                </div>
                            </div>
                           
                        </div>-->
                        <div class="mb-3">
                            <label for="email" class="form-label">승인여부</label>
                            <select class="form-select" :v-model="editForm.status" @change="changeStatus($event)" id="status">
                                <option v-for="(label, value) in statusLabel" :key="value" :value="value" :selected="value == editForm.status">{{ label }}</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="user-title" class="form-label"
                                >사진(본인 확인용)</label
                            >
                            <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUpload">
                                파일 첨부
                            </button>
                            <input type="file" @change="handleFileUpload" ref="fileInputRef" style="display:none" id="file_user_photo">
                            <div class="text-start tc-light-gray" v-if="editForm.file_user_photo_name">
                                사진 파일 : <a :href=fileImgUrl download>{{ editForm.file_user_photo_name }}</a>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">회원유형</label>
                            <select class="form-select" :v-model="editForm.role" @change="changeRoles($event)" id="role">
                                <option value="user">일반</option>
                                <option value="dealer">딜러</option>
                            </select>
                        </div>
                        <div v-if="editForm.role == 'dealer'">
                            <div class="mb-3">
                                <label for="user-title" class="form-label"
                                    >소속상사</label
                                >
                                <input
                                    v-model = "editForm.company"
                                    type="text"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <label for="user-title" class="form-label">소속상사 주소</label>
                                
                                <input
                                v-model="editForm.company_post"
                                    class="input-dis form-control"
                                    placeholder="우편번호"
                                    readonly
                                    />
                                    <button type="button" class="search-btn" @click="editPostCode('daumPostcodeInput')">검색</button>
                                    </div>
                                    <div>
                                    <div class="form-group">
                                    <input
                                        v-model="editForm.company_addr1"
                                        class="input-dis form-control"
                                        placeholder="주소"
                                        readonly
                                    />
                                   <!-- <span><button type="button" @click="editPostCode('daumPostcodeInput')">주소버튼</button></span>-->
                                </div>
                                <input
                                    v-model="editForm.company_addr2"
                                    type="text"
                                    class="form-control"
                                    placeholder="상세주소"
                                />
                                <div id="daumPostcodeInput" style="display: none; border: 1px solid; width: 100%; height: 466px; margin: 5px 0px; position: relative">
                                    <img src="//t1.daumcdn.net/postcode/resource/images/close.png" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" @click="closePostcode('daumPostcodeInput')">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="user-title" class="form-label my-3">인수차량 도착지 주소</label>
                                <input
                                    v-model="editForm.receive_post"
                                    class="input-dis form-control"
                                    placeholder="우편번호"
                                    readonly
                                />
                                <button type="button" class="search-btn" @click="editPostCodeReceive('daumPostcodeDealerReceiveInput')">검색</button>
                                </div>
                            <div class="form-group">
                                <div>
                                    <input
                                        v-model="editForm.receive_addr1"
                                        class="input-dis form-control"
                                        placeholder="주소"
                                        readonly
                                    />
                                  <!--  <span><button type="button" @click="editPostCodeReceive('daumPostcodeDealerReceiveInput')">주소버튼</button></span>-->
                                </div>
                                <input
                                    v-model="editForm.receive_addr2"
                                    type="text"
                                    class="form-control"
                                    placeholder="상세주소"
                                />
                                <div id="daumPostcodeDealerReceiveInput" style="display: none; border: 1px solid; width: 100%; height: 466px; margin: 5px 0px; position: relative">
                                    <img src="//t1.daumcdn.net/postcode/resource/images/close.png" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" @click="closePostcode('daumPostcodeDealerReceiveInput')">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="user-title" class="form-label"
                                    >소개</label
                                >
                                <textarea
                                    v-model = "editForm.introduce"
                                    type="text"
                                    class="custom-textarea mt-2"
                                ></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="user-title" class="form-label"
                                    >사업자 등록증</label
                                >
                                <input type="file" @change="handleFileUploadBiz" ref="fileInputRefBiz" style="display:none">
                                <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadBiz">
                                    파일 첨부
                                </button>
                                <div class="text-start tc-light-gray" v-if="editForm.file_user_biz_name">
                                    사업자 등록증 파일 : <a :href=fileBizUrl download>{{ editForm.file_user_biz_name }}</a>
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
                                <div class="text-start tc-light-gray" v-if="editForm.file_user_sign_name">
                                    매도용인감정보 파일 : <a :href=fileSignUrl download>{{ editForm.file_user_sign_name }}</a>
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
                                <div class="text-start tc-light-gray" v-if="editForm.file_user_cert_name">
                                    매매업체 대표증 / 종사원증 파일 : <a :href=fileCertUrl download>{{ editForm.file_user_cert_name }}</a>
                                </div>
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
                                <span v-else>저장</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { onMounted, reactive, ref, watchEffect } from "vue";
import { useRoute } from "vue-router";
import useRoles from "@/composables/roles";
import useUsers from "@/composables/users";
import { useForm, useField, defineRule } from "vee-validate";
import { required, min } from "@/validation/rules";
import { useStore } from 'vuex';
import { cmmn } from '@/hooks/cmmn';

defineRule("required", required);
defineRule("min", min);

const route = useRoute();
const { roleList, getRoleList } = useRoles();
const { openPostcode , closePostcode, wicas} = cmmn();

const {
    editForm,
    updateUser,
    getUser,
    user,
    validationErrors,
    isLoading,
} = useUsers();

let userInfo = {};

const fileInputRefBiz = ref(null);
const fileInputRefSign = ref(null);
const fileInputRefCert = ref(null);
const fileInputRef = ref(null);
const fileImgUrl = ref('');
const fileSignUrl = ref('');
const fileCertUrl = ref('');
const fileBizUrl = ref('');


let created_at;
let email;
let updated_at;

const store = useStore();
let statusLabel;

// Define a validation schema
const schema = {
    name: { required: "이름은 필수 항목입니다."},

};

onMounted(async () => {
    statusLabel = wicas.enum(store).users();
    userInfo = await getUser(route.params.id);
    fileImgUrl.value = userInfo.files.file_user_photo[0].original_url;
    fileSignUrl.value = userInfo.files.file_user_sign[0].original_url;
    fileCertUrl.value = userInfo.files.file_user_cert[0].original_url;
    fileBizUrl.value = userInfo.files.file_user_biz[0].original_url;

    console.log(user.value);
    await getRoleList();
    /** 
    for (const roleName of response.roles) {
        const findRoleId = roleList.value.find(role => role.name === roleName);
        user.role = findRoleId.id;
    }*/
   
    watchEffect(() => {
        editForm.name = user.value.name;
        created_at = user.value.created_at;
        updated_at = user.value.updated_at;
        email = user.value.email;
        document.getElementById("status").value = user.value.status;
        document.getElementById("role").value = user.value.roles[0];
        editForm.status = user.value.status;
        editForm.role = user.value.roles[0];
        editForm.phone = user.value.phone;
        if(user.value.files.file_user_photo){
            editForm.file_user_photo_name = user.value.files.file_user_photo[0].name;
        }
        if(user.value.files.file_user_biz){
            editForm.file_user_biz_name = user.value.files.file_user_biz[0].name;
        }
        if(user.value.files.file_user_sign){
            editForm.file_user_sign_name = user.value.files.file_user_sign[0].name;
        }
        if(user.value.files.file_user_cert){
            editForm.file_user_cert_name = user.value.files.file_user_cert[0].name;
        }
        
        if(user.value.dealer){
            editForm.company = user.value.dealer.company;
            editForm.company_post = user.value.dealer.company_post;
            editForm.company_addr1 = user.value.dealer.company_addr1;
            editForm.company_addr2 = user.value.dealer.company_addr2;
            editForm.introduce = user.value.dealer.introduce;
            editForm.receive_post = user.value.dealer.receive_post;
            editForm.receive_addr1 = user.value.dealer.receive_addr1;
            editForm.receive_addr2 = user.value.dealer.receive_addr2;
        } 

    })


});

function submitForm() {
    /**
    validate().then((form) => {
        console.log(user);
        
        if(user.role == 'dealer'){
            schema.company = { required };
            schema.company_post = { required };
            schema.company_addr1 = { required };
            schema.company_addr2 = { required };
            schema.receive_post = { required };
            schema.receive_addr1 = { required };
            schema.receive_addr2 = { required };

        } 
        console.log(JSON.stringify(user));
        if (form.valid) updateUser(user,dealer,route.params.id);
    }); */
    updateUser(editForm,route.params.id);
}

function editPostCode(elementName) {
  openPostcode(elementName)
    .then(({ zonecode, address }) => {
        editForm.company_post = zonecode;
        editForm.company_addr1 = address;
    })
}
function editPostCodeReceive(elementName) {
    openPostcode(elementName)
    .then(({ zonecode, address }) => {
        editForm.receive_post = zonecode;
        editForm.receive_addr1 = address;
    })
}

function changeStatus(event) {
    editForm.status = event.target.value;
    isDealer = true;
}

function changeRoles(event) {
    editForm.role = event.target.value;
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
    console.log('fileInfo======');
    console.log(file);
    if (file) {
        editForm.file_user_photo = file;
        editForm.file_user_photo_name = file.name;
        fileImgUrl.value = URL.createObjectURL(file);
        //console.log("File:", file.name);
    }
}

function handleFileUploadBiz(event) {
    const file = event.target.files[0];
    if (file) {
        editForm.file_user_biz = file;
        editForm.file_user_biz_name = file.name;
        fileBizUrl.value = URL.createObjectURL(file);
        //console.log("Business registration file:", file.name);
    }
}

function handleFileUploadSign(event) {
    const file = event.target.files[0];
    if (file) {
        editForm.file_user_sign = file;
        editForm.file_user_sign_name = file.name;
        fileSignUrl.value = URL.createObjectURL(file);
        //console.log("Signature file:", file.name);
    }
}

function handleFileUploadCert(event) {
    const file = event.target.files[0];
    if (file) {
        editForm.file_user_cert = file;
        editForm.file_user_cert_name = file.name;
        fileCertUrl.value = URL.createObjectURL(file);
        //console.log("Certification file:", file.name);
    }
}

</script>
<style>
h4 {
    position: relative;
    padding-bottom: 15px;
}
.search-btn {
    transform: translateY(-108%) !important;
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
</style>