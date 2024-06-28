<template>
    <h4><span class="admin-icon admin-icon-menu"></span>내 정보</h4>
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
                                    v-model = "adminEditForm.name"
                                    type="text"
                                    class="form-control"
                                    required
                                />
                            </div>
                            <div class="mb-3">
                                <label for="user-title" class="form-label"
                                    >가입일</label
                                >
                                <input
                                    v-model = "created_at"
                                    type="text"
                                    class="form-control tc-light-gray input-dis"
                                    readonly
                                />
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input
                                    v-model = "email"
                                    type="email"
                                    class="form-control tc-light-gray input-dis"
                                    readonly
                                />
                                
                            </div>
                            <div class="mb-3">
                                <label for="user-title" class="form-label"
                                    >전화번호</label
                                >
                                <input
                                    v-model = "adminEditForm.phone"
                                    type="text"
                                    class="form-control"
                                    required
                                />
                                <div class="text-danger mt-1">
                                    <div v-for="message in validationErrors?.phone">
                                        {{ message }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="user-title" class="form-label"
                                    >현재 비밀번호</label
                                >
                                <input
                                    v-model="adminEditForm.currentPw"
                                    type="password"
                                    class="form-control"
                                    autocomplete 
                                    required
                                />
                                <div class="text-danger mt-1">
                                    <div v-for="message in validationErrors?.currentPwLabel">
                                        {{ message }}
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="user-title" class="form-label"
                                    >새 비밀번호</label
                                >
                                <input
                                    v-model = "adminEditForm.password"
                                    type="password"
                                    class="form-control"
                                    autocomplete 
                                />
                                <div class="text-danger mt-1">
                                    <div v-for="message in validationErrors?.password">
                                        {{ message }}
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="user-title" class="form-label"
                                    >새 비밀번호 확인</label
                                >
                                <input
                                    v-model = "adminEditForm.password_confirmation"
                                    type="password"
                                    class="form-control"
                                    autocomplete 
                                />
                                <div class="text-danger mt-1">
                                    <div v-for="message in validationErrors?.password_confirmation">
                                        {{ message }}
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
    import { onMounted, watchEffect } from "vue";
    import useUsers from "@/composables/users";
    import { useStore } from 'vuex';
    
    const store = useStore();
    
    const {
        adminEditForm,
        updateMyInfo,
        getUser,
        validationErrors,
        isLoading,
    } = useUsers();
    
    let created_at;
    let email;
    let userId;
    
    onMounted(async () => {
        const user =store.getters['auth/user'];
        userId = user.id;
        console.log(userId);
        const response = await getUser(userId);
    
        watchEffect(() => {
            adminEditForm.name = response.name;
            created_at = response.created_at;
            email = response.email;
            adminEditForm.phone = response.phone;
            adminEditForm.currentPw='';
            adminEditForm.password = '';
            adminEditForm.password_confirmation ='';
        })
    });


    function submitForm() {
        updateMyInfo(adminEditForm,userId);
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