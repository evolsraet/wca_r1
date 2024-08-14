<template>
<h4><span class="admin-icon admin-icon-menu"></span>회원관리</h4>
    <div class="container my-5">
        <div class="col-md-10">
            <div class="card border-0 shadow-none">
                <div class="card-body">
                    <!-- 여기부터 수정 -->
                    <UserRegisterForm/>
                    
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { onMounted, reactive, ref, watchEffect } from "vue";
import UserRegisterForm from "@/views/import/UserRegister.vue";

onMounted(async () => {

});

</script>
<style>
h4 {
    position: relative;
    padding-bottom: 15px;
}
.search-btn {
    transform: translateY(-108%) !important;
    right: 5px !important;
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