<template>
    <div class="container mov-wide">
        <div class="container my-5" style="margin-top: 100px !important;">
            <div>
                <h5 class="my-3">비밀번호 초기화</h5>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form @submit.prevent="submitForgotPasswordBtn">
                            <div class="">
                                <!-- phone -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">전화번호</label>
                                    <input v-model="phone" id="email" type="text" class="form-control" required autofocus autocomplete="username">
                                </div>
                               
                                <!-- Buttons -->
                                <div class="flex items-center justify-end mt-4">
                                    <button class="btn btn-primary w-100" >
                                        비밀번호 초기화
                                    </button>
                                </div>
                               <p class="text-secondary opacity-50 text-center mt-3">비밀번호 초기화 링크를 보내드립니다.</p>
                               <div v-if="passwordChgLink">
                                <router-link :to="{ name: 'auth.resetPasswordLogin', params: { code: passwordChgLinkCode }}">
                                    <p>임시링크</p>
                                </router-link>
                               </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Footer />
</template>

<script setup>
import useAuth from '@/composables/auth'
import Footer from "@/views/layout/footer.vue"
import { ref } from 'vue';
const {  submitForgotPassword } = useAuth();
    const phone = ref('');
    const passwordChgLink = ref('');
    const passwordChgLinkCode = ref('');

    async function submitForgotPasswordBtn(){
        const submitResult= await submitForgotPassword(phone.value);
        if(submitResult.isSuccess){
            passwordChgLink.value=submitResult.data.link;
            passwordChgLinkCode.value = submitResult.data.encryptCode;
        }
    }

</script>

<style scoped>

</style>
