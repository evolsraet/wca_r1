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
                                    v-model = "user.name"
                                    type="text"
                                    class="form-control"
                                />
                                <div class="text-danger mt-1">
                                    {{ errors.name }}
                                </div>
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
                                />
                                <div class="text-danger mt-1">
                                    {{ errors.email }}
                                </div>
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
                                />
                                <div class="text-danger mt-1">
                                    {{ errors.password }}
                                </div>
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
                                />
                                <div class="text-danger mt-1">
                                    {{ errors.password }}
                                </div>
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
                                        >소속상사</label
                                    >
                                    <input
                                        v-model = "dealer.company"
                                        type="text"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group">
                                    <label for="user-title" class="form-label">소속상사 주소</label>
    
                                    <input
                                        v-model="dealer.company_post"
                                        type="text"
                                        class="form-control"
                                        placeholder="post"
                                        readonly
                                    />
                                    <div>
                                        <input
                                            v-model="dealer.company_addr1"
                                            type="text"
                                            class="form-control"
                                            placeholder="주소"
                                            readonly
                                        />
                                        <span><button type="button" @click="editPostCode('daumPostcodeInput')">주소버튼</button></span>
                                    </div>
                                    <input
                                        v-model="dealer.company_addr2"
                                        type="text"
                                        class="form-control"
                                        placeholder="상세주소"
                                    />
                                    <div id="daumPostcodeInput" style="display: none; border: 1px solid; width: 100%; height: 466px; margin: 5px 0px; position: relative">
                                        <img src="//t1.daumcdn.net/postcode/resource/images/close.png" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" @click="closePostcode('daumPostcodeInput')">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="user-title" class="form-label">인수차량 도착지 주소</label>
                                    <input
                                        v-model="dealer.receive_post"
                                        type="text"
                                        class="form-control"
                                        placeholder="post"
                                        readonly
                                    />
                                    <div>
                                        <input
                                            v-model="dealer.receive_addr1"
                                            type="text"
                                            class="form-control"
                                            placeholder="주소"
                                            readonly
                                        />
                                        <span><button type="button" @click="editPostCodeReceive('daumPostcodeDealerReceiveInput')">주소버튼</button></span>
                                    </div>
                                    <input
                                        v-model="dealer.receive_addr2"
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
                                        v-model = "dealer.introduce"
                                        type="text"
                                        class="form-control no-resize mt-2"
                                    ></textarea>
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
    import { onMounted, reactive, ref } from "vue";
    import { useRoute } from "vue-router";
    import useRoles from "@/composables/roles";
    import useUsers from "@/composables/users";
    import { useForm, useField, defineRule } from "vee-validate";
    import { required, min } from "@/validation/rules";
    import { cmmn } from '@/hooks/cmmn';
    
    defineRule("required", required);
    defineRule("min", min);
    
    const route = useRoute();
    const { roleList, getRoleList } = useRoles();
    const { openPostcode , closePostcode} = cmmn();
    const userRoles = ref("");

    const {
        adminStoreUser,
        validationErrors,
        isLoading,
    } = useUsers();
    
    let created_at;
    let email;

    
    // Define a validation schema
    const schema = {
        name: { required },
        password: { required },
        password_confirmation: { required },
        phone: { required },
        role: { required },
    };
    
    // Create a form context with the validation schema
    const { validate, errors, resetForm } = useForm({ validationSchema: schema });
    // Define actual fields for validation
    const { value: name } = useField("name", null, { initialValue: "" });
    const { value: password } = useField("password", null, { initialValue: "" });
    const { value: password_confirmation } = useField("password_confirmation", null, { initialValue: "" });
    const { value: phone } = useField("phone", null, { initialValue: "" });
    const { value: role } = useField("role", null, { initialValue: "" });
    const { value: company } = useField("company", null, { initialValue: "" });
    const { value: company_post } = useField("company_post", null, { initialValue: "" });
    const { value: company_addr1 } = useField("company_addr1", null, { initialValue: "" });
    const { value: company_addr2 } = useField("company_addr2", null, { initialValue: "" });
    const { value: receive_post } = useField("receive_post", null, { initialValue: "" });
    const { value: receive_addr1 } = useField("receive_addr1", null, { initialValue: "" });
    const { value: receive_addr2 } = useField("receive_addr2", null, { initialValue: "" });
    const introduce = ref("");
    
    const user = reactive({
        name,
        password,
        password_confirmation,
        phone,
        role:"user"
    });
    const dealer = reactive({
        company,
        company_post,
        company_addr1,
        company_addr2,
        introduce,
        receive_post,
        receive_addr1,
        receive_addr2
    })
    
    function submitForm() {
        validate().then((form) => {
            if(userRoles=="dealer"){
                schema.company = { required };
                schema.company_post = { required };
                schema.company_addr1 = { required };
                schema.company_addr2 = { required };
                schema.receive_post = { required };
                schema.receive_addr1 = { required };
                schema.receive_addr2 = { required };
    
            }
            console.log(JSON.stringify(user));
            adminStoreUser(user,dealer);
        });
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
    </style>