import { ref, inject } from "vue";
import { useRouter } from "vue-router";
import store from "../store";

export default function useProfile() {
    const profile = ref({
        name: "",
        email: "",
    });

    const router = useRouter();
    const validationErrors = ref({});
    const isLoading = ref(false);
    const swal = inject("$swal");

    const getProfile = async () => {
        profile.value = store.getters["auth/user"];
    };

    const updateProfile = async (profile) => {
        if (isLoading.value) return;

        isLoading.value = true;
        validationErrors.value = {};

        wicac.conn()
        .url(`/api/users/${user.id}`) //호출 URL
        .param(profile)
        .callback(function(result) {
            if(result.isSuccess){
                store.commit("auth/SET_USER", result.data);
                wica.ntcn(swal).icon('S').title('정상 처리 되었습니다.').fire();
            }else{
                validationErrors.value = result.rawData.response.data.errors;
            }
        })
        .put();
    };

    return {
        profile,
        getProfile,
        updateProfile,
        validationErrors,
        isLoading,
    };
}
