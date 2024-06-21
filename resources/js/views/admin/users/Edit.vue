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
                                >가입일</label
                            >
                            <input
                                v-model = "created_at"
                                type="text"
                                class="form-control"
                                readonly
                            />
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                v-model = "email"
                                type="email"
                                class="form-control"
                                readonly
                            />
                            
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
                            <select class="form-select" :v-model="user.status" @change="changeStatus($event)" id="status">
                                <option value="ok">정상</option>
                                <option value="ask">심사중</option>
                                <option value="reject">거절</option>
                            </select>
                            <!--
                            <div class="text-start status-selector">
                                <input type="radio" name="status" v-model="user.status" value="ok" id="ok">
                                <label for="ok" class="mx-2">정상</label>
                                <input type="radio" name="status" value="ask" v-model="user.status" id="ask">
                                <label for="ask">심사중</label>
                                <input type="radio" name="status" value="reject" v-model="user.status" id="reject">
                                <label for="reject" class="mx-2">거절</label>
                            </div>-->
                            
                        </div>
                        <div v-if="dealer_info">
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
                                        v-model="dealer.company_addr1"
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
                                v-model="dealer.introduce"
                                class="form-control no-resize mt-2"
                                :style="{ height: `${height}px`, overflowY: 'hidden' }"
                                @input="autoResize"
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
import { onMounted, reactive, ref, watchEffect } from "vue";
import { useRoute } from "vue-router";
import useRoles from "@/composables/roles";
import useUsers from "@/composables/users";
import { useForm, useField, defineRule } from "vee-validate";
import { required, min } from "@/validation/rules";
import { cmmn } from '@/hooks/cmmn';
import { watch } from "vue";

defineRule("required", required);
defineRule("min", min);

const height = ref(100);
const route = useRoute();
const { roleList, getRoleList } = useRoles();
const { openPostcode , closePostcode} = cmmn();

const {
    updateUser,
    getUser,
    validationErrors,
    isLoading,
} = useUsers();

let created_at;
let email;
let dealer_info;

// Define a validation schema
const schema = {
    name: { required },
    status: { required },
};

// Create a form context with the validation schema
const { validate, errors, resetForm } = useForm({ validationSchema: schema });
// Define actual fields for validation
const { value: name } = useField("name", null, { initialValue: "" });
const { value: status } = useField("status", null, { initialValue: "" });
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
    status,
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
onMounted(async () => {
    const response = await getUser(route.params.id);
    await getRoleList();

    /** 
    for (const roleName of response.roles) {
        const findRoleId = roleList.value.find(role => role.name === roleName);
        user.role = findRoleId.id;
    }*/
    watchEffect(() => {
        user.name = response.name;
        created_at = response.created_at;
        email = response.email;
        document.getElementById("status").value = response.status;
        user.phone = response.phone;
        if(response.dealer){
            dealer_info = response.dealer;
            dealer.company = response.dealer.company;
            dealer.company_post = response.dealer.company_post;
            dealer.company_addr1 = response.dealer.company_addr1;
            dealer.company_addr2 = response.dealer.company_addr2;
            dealer.introduce = response.dealer.introduce;
            dealer.receive_post = response.dealer.receive_post;
            dealer.receive_addr1 = response.dealer.receive_addr1;
            dealer.receive_addr2 = response.dealer.receive_addr2;
        } 

    })


});

function submitForm() {
    validate().then((form) => {
        if(dealer_info){
            schema.company = { required };
            schema.company_post = { required };
            schema.company_addr1 = { required };
            schema.company_addr2 = { required };
            schema.receive_post = { required };
            schema.receive_addr1 = { required };
            schema.receive_addr2 = { required };

        }
        if (form.valid) updateUser(user,dealer,route.params.id);
    });
}
function autoResize(event) {
    event.target.style.height = 'auto'; 
    event.target.style.height = `${event.target.scrollHeight}px`; 
    height.value = event.target.scrollHeight; 
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

function changeStatus(event) {
  user.status = event.target.value;
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