import { ref, reactive, inject } from "vue";
import { useRouter } from "vue-router";
import { AbilityBuilder, createMongoAbility } from "@casl/ability";
import { ABILITY_TOKEN } from "@casl/vue";
import store from "../store";

let user = reactive({
    name: "",
    email: "",
});

export default function useAuth() {
    const processing = ref(false);
    const validationErrors = ref({});
    const router = useRouter();
    const swal = inject("$swal");
    const ability = inject(ABILITY_TOKEN);

    const loginForm = reactive({
        email: "",
        password: "",
        remember: false,
        role: {
            name: "beta",
        },
    });

    const forgotForm = reactive({
        email: "",
    });

    const resetForm = reactive({
        email: "",
        token: "",
        password: "",
        password_confirmation: "",
    });

    const registerForm = reactive({
        name: "",
        email: "",
        phone:"",
        password: "",
        password_confirmation: "",
        dealer: null,
    })

    const submitLogin = async () => {
        if (processing.value) return;
      
        processing.value = true;
        validationErrors.value = {};
      
        try {
          const userData = await store.dispatch('auth/login', loginForm.value);
          await store.dispatch("auth/getUser");
          await store.dispatch("auth/getAbilities");
          Swal.fire({
            icon: "success",
            title: "로그인",
            showConfirmButton: false,
            timer: 1500,
          });
          if (userData.roles.includes('admin')) {
            router.push({ name: "admin.index" });
          } else if (userData.roles.includes('dealer')) {
            router.push({ name: "dealer.index" });
          } else {
            router.push({ name: "home" });
          }
        } catch (error) {
          if (!error.response) {
            // 네트워크 오류
            Swal.fire({
              icon: "error",
              title: "Network Error",
              text: "Please check your internet connection and try again.",
              showConfirmButton: true,
            });
          } else if (error.response.data.status === 'fail') {
            Swal.fire({
              icon: "error",
              title: "Error",
              text: error.response.data.message,
              showConfirmButton: true,
            });
          } else {
      
            Swal.fire({
              icon: "error",
              text: error.response.data.message || "관리자에게 문의해 주세요.",
              showConfirmButton: true,
            });
            if (error.response?.data) {
              validationErrors.value = error.response.data.errors;
            }
          }
          console.log(error);
          console.log(error.response?.data.message);
          console.log(error.response?.data.errors);
        } finally {
          processing.value = false;
        }
      };
    

    const submitRegister = async () => {
        if (processing.value) return;

        processing.value = true;
        validationErrors.value = {};

    let payload = {
            user: {
                name: registerForm.name,
                email: registerForm.email,
                phone: registerForm.phone,
                password: registerForm.password,
                password_confirmation: registerForm.password_confirmation,
            }
        };
        if (registerForm.dealer) {
            payload.dealer ={
                name: registerForm.dealerName,
                phone: registerForm.dealerContact,
                birthday: registerForm.dealerBirthDate,
                company: registerForm.dealerCompany,
                company_duty: registerForm.dealerCompanyDuty,
                company_post: registerForm.dealerCompanyPost,
                company_addr1: registerForm.dealercompany_addr1,
                company_addr2: registerForm.dealercompany_addr2,
                receive_post: registerForm.dealerReceivePost,
                receive_addr1: registerForm.dealerReceiveAddr1,
                receive_addr2: registerForm.dealerReceiveAddr2,
                introduce: registerForm.introduce,
            }
            payload.user.role = 'dealer'; 
        } else {
            payload.user.role = 'user';
        } 
        console.log("전체 데이터:" , payload.user.role);
        console.log("역할:" , payload);
        const formData = new FormData();
        formData.append('user',JSON.stringify(payload.user));
        formData.append('dealer',JSON.stringify(payload.dealer));

        if(registerForm.file_user_photo){
            formData.append('file_user_photo', registerForm.file_user_photo);
        }
        if(registerForm.file_user_biz){
            formData.append('file_user_biz', registerForm.file_user_biz);
        }
        if(registerForm.file_user_cert){
            formData.append('file_user_cert', registerForm.file_user_cert);
        }
        if(registerForm.file_user_sign){
            formData.append('file_user_sign', registerForm.file_user_sign);
        }

        await axios
            .post("/api/users", formData,{
                headers:{ 
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(async(response) => {
                swal({
                    icon: "success",
                    showConfirmButton: false,
                    timer: 1500,
                });
                console.log(response);
                await router.push({ name: "auth.login" });
            })
            .catch((error) => {
                console.log(error);
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors;
                }
            })
            .finally(() => (processing.value = false));
    };

    const submitForgotPassword = async () => {
        swal({
            icon: 'error',
            title: '준비중',
            showConfirmButton: false
        });
        if (processing.value) return;

        processing.value = true;
        validationErrors.value = {};

        await axios
            .post("/api/forget-password", forgotForm)
            .then(async (response) => {
                swal({
                    icon: "success",
                    title: "비밀번호 재설정 링크를 이메일로 보냈습니다! 메일함을 확인해 주세요.",
                    showConfirmButton: false,
                    timer: 1500,
                });
                // await router.push({ name: 'admin.index' })
            })
            .catch((error) => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors;
                }
            })
            .finally(() => (processing.value = false));
    };

    const submitResetPassword = async () => {
        if (processing.value) return;

        processing.value = true;
        validationErrors.value = {};

        await axios
            .post("/api/reset-password", resetForm)
            .then(async (response) => {
                swal({
                    icon: "success",
                    title: "비밀번호가 정상적으로 변경되었습니다.",
                    showConfirmButton: false,
                    timer: 1500,
                });
                await router.push({ name: "auth.login" });
            })
            .catch((error) => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors;
                }
            })
            .finally(() => (processing.value = false));
    };

    const loginUser = () => {
        user = store.state.auth.data;
        // Cookies.set('loggedIn', true)
        getAbilities();
    };

    const getUser = async () => {
        if (store.getters["auth/authenticated"]) {
            await store.dispatch("auth/getUser");
            await store.dispatch("auth/getAbilities");
            await loginUser();
        }
    };

    const logout = async () => {
        if (processing.value) return;

        processing.value = true;

        axios
            .post("/logout")
            .then((response) => {
                user.name = "";
                user.email = "";
                store.dispatch("auth/logout");
                router.push({ name: "auth.login" });
                // console.log();
            })
            .catch((error) => {
                // swal({
                //     icon: 'error',
                //     title: error.response.status,
                //     text: error.response.statusText
                // })
            })
            .finally(() => {
                processing.value = false;
                // Cookies.remove('loggedIn')
            });
    };

    const getAbilities = async () => {
        await axios.get("/api/users/abilities").then((response) => {
            console.log(response);
            const permissions = response.data.data;
            const { can, rules } = new AbilityBuilder(createMongoAbility);

            can(permissions);

            ability.update(rules);


            //store.state.auth.user.act = permissions;
        });
    };
    


    return {
        loginForm,
        registerForm,
        forgotForm,
        resetForm,
        validationErrors,
        processing,
        submitLogin,
        submitRegister,
        submitForgotPassword,
        submitResetPassword,
        user,
        getUser,
        logout,
        getAbilities,
    };
}
