import axios from "axios";

export default {
    namespaced: true,
    state: {
        authenticated: false,
        user: {},
    },
    getters: {
        authenticated(state) {
            return state.authenticated;
        },
        user(state) {
            return state.user;
        },
    },
    mutations: {
        SET_AUTHENTICATED(state, value) {
            state.authenticated = value;
        },
        SET_USER(state, value) {
            state.user = value;
        },
        SET_USER_ACT(state, value) {
            state.user.act = value;
            console.log(state.user);
            console.log(state.user);
            console.log(state.user);
            console.log(state.user);
            console.log(state.user);
            console.log(state.user);
            
        },
    },
    actions: {
        login({ commit }) {
            return axios
                .get("/api/users/me")
                .then(({ data }) => {
                    commit("SET_USER", data.data);
                    commit("SET_AUTHENTICATED", true);
                })
                .catch(({ res }) => {
                    commit("SET_USER", {});
                    commit("SET_AUTHENTICATED", false);
                });
        },
        getUser({ commit }) {
            return axios
                .get("/api/users/me")
                .then(({ data }) => {
                    // if (data.status == "ok") {
                    commit("SET_USER", data.data);
                    commit("SET_AUTHENTICATED", true);
                    // router.push({name: 'dashboard'})
                    // }
                    // else {
                    //     commit('SET_USER', {})
                    //     commit('SET_AUTHENTICATED', false)
                    // }

                })
                .catch(({ res }) => {
                    commit("SET_USER", {});
                    commit("SET_AUTHENTICATED", false);
                });
        },
        getAbilities({ commit }) {
            return axios
                .get("/api/users/abilities")
                .then(({ data }) => {
                    commit("SET_USER_ACT", data.data);
                    console.log(then);
                })
                .catch(({ res }) => {
                   
                });
        },
        logout({ commit }) {
            commit("SET_USER", {});
            commit("SET_AUTHENTICATED", false);
            console.log(then);
            router.push({ name: "auth.login" });
        },
    },
};
