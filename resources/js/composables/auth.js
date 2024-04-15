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
        password: "",
        password_confirmation: "",
        dealer: null,
    });

    const submitLogin = async () => {
        if (processing.value) return;

        processing.value = true;
        validationErrors.value = {};

        await axios
            .post("/login", loginForm)
            .then(async (response) => {
                const userData = response.data.data.user
                await store.dispatch("auth/getUser");
                await loginUser();
                swal({
                    icon: "success",
                    title: "Login successfully",
                    showConfirmButton: false,
                    timer: 1500,
                });
                if (userData.roles.includes('admin')) {
                    await router.push({ name: "admin.index" });
                } else if (userData.roles.includes('dealer')) {
                    await router.push({ name: "dealer.index2" }); 
                } else {
                    await router.push({ name: "autction.index" }); 
                }
            })
            .catch((error) => {
                console.log(error);
                console.log(error.response?.data.message);
                console.log(error.response?.data.errors);
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors;
                }
            })
            .finally(() => (processing.value = false));
    };

    const submitRegister = async () => {
        if (processing.value) return;

        processing.value = true;
        validationErrors.value = {};

        let payload = {
            name: registerForm.name,
            email: registerForm.email,
            password: registerForm.password,
            password_confirmation: registerForm.password_confirmation,
            dealer: registerForm.dealer, 
        };
        console(name);
        if (registerForm.dealer) {
            payload.roles = ['dealer']; 
        } else {
            payload.roles = ['user'];
        }

        await axios
            .post("/register", registerForm)
            .then(async (response) => {
                // await store.dispatch('auth/getUser')
                // await loginUser()
                swal({
                    icon: "success",
                    title: "Registration successfully",
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

    const submitForgotPassword = async () => {
        if (processing.value) return;

        processing.value = true;
        validationErrors.value = {};

        await axios
            .post("/api/forget-password", forgotForm)
            .then(async (response) => {
                swal({
                    icon: "success",
                    title: "We have emailed your password reset link! Please check your mail inbox.",
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
                    title: "Password successfully changed.",
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
        });
    };
    
    /* 입찰 데이터 ::get */ 
    const getBids = async () => {

        processing.value = true;
        let bidsData = null; 
        try {
            const response = await axios.get("/api/bids");
            bidsData = response.data.data; 
        } catch (error) {
            console.error('Error fetching bids:', error);
        } finally {
            processing.value = false;
            return bidsData; 
        }
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
        getBids,
        user,
        getUser,
        logout,
        getAbilities,
    };
}
