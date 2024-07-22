<template>
    <div class="container mov-wide">
        <div class="container my-5">
            <div>
                <h5 class="my-3">비밀번호 초기화</h5>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form @submit.prevent="submitForgotPassword">
                            <div class="">
                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">이메일</label>
                                    <input v-model="forgotForm.email" id="email" type="email" class="form-control" required autofocus autocomplete="username">
                                    <!-- Validation Errors -->
                                    <div class="text-danger mt-1">
                                        <div v-for="message in validationErrors?.email">
                                            {{ message }}
                                        </div>
                                    </div>
                                </div>
                                <!-- Buttons -->
                                <div class="flex items-center justify-end mt-4">
                                    <button class="btn btn-primary" :class="{ 'opacity-25': processing },'w-100'" :disabled="processing">
                                        비밀번호 초기화
                                    </button>
                                </div>
                                <p class="text-secondary opacity-50 text-center mt-3">비밀번호 초기화 링크를 메일로 보내드립니다.</p>
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
const { forgotForm, validationErrors, processing, submitForgotPassword } = useAuth();
</script>

<style scoped>

</style>
