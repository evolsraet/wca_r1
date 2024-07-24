<template>
    <div class="container">
        
    </div>
</template>

<script setup>
import useAuth from '@/composables/auth';
import {useRoute, useRouter} from "vue-router";
import {onMounted, inject} from "vue";
import { useStore } from 'vuex';
import { cmmn } from '@/hooks/cmmn';

const { wica } = cmmn();
const { forgotPasswordLogin, getUserMe } = useAuth();

const route = useRoute();
const router = useRouter();
const store = useStore();
const swal = inject("$swal");

onMounted(async() => {
    const code = route.params.code;
    const result =await forgotPasswordLogin(code);
    if(result.isSuccess){

        wica.ntcn(swal)
        .icon('I') //E:error , W:warning , I:info , Q:question
        .callback(async function(result2) {
            if(result2.isOk){
                const userInfo = await getUserMe();
                let userData = userInfo.data;

                await store.dispatch("auth/getUser");
                await store.dispatch("auth/getAbilities");
                await store.dispatch("enums/getData");
                await store.dispatch("fields/getData");

                const guestClickedAuction = localStorage.getItem('guestClickedAuction');
                
                if (userData.roles.includes('admin')) {
                    router.push({ name: "myinfo.edit" });
                } else if (userData.roles.includes('dealer')) {
                    router.push({ name: "edit-profile" });
                } else if (guestClickedAuction !== 'true' &&userData.roles.includes('user')) {
                    router.push({ name: "edit-profile" });
                } else if (guestClickedAuction === 'true' && userData.roles.includes('user')) {
                    router.push({ name: "sell" });
                    localStorage.removeItem('guestClickedAuction');  // 플래그 삭제
                } else {
                    router.push({ name: "home" });
                }
            }
        })
        .alert(result.rawData.data.message);
            
    }else{
        store.commit("SET_USER", {});
        store.commit("SET_AUTHENTICATED", false);
        store.commit('SET_ERROR_MESSAGE', result.msg); 
    }
})

</script>

<style scoped>

</style>
