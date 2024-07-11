import { cmmn } from '@/hooks/cmmn';
const { wicac } = cmmn();

export default {
  namespaced: true,
  state: {
    authenticated: false,
    user: {},
    errorMessage: '', 
  },
  getters: {
    authenticated(state) {
      return state.authenticated;
    },
    user(state) {
      return state.user;
    },
    errorMessage(state) {
      return state.errorMessage;
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
    },
    SET_ERROR_MESSAGE(state, value) {
      state.errorMessage = value;
    },
    CLEAR_ERROR_MESSAGE(state) {
      state.errorMessage = '';
    },
  },
  actions: {
    async login({ commit }, credentials) {
      commit('CLEAR_ERROR_MESSAGE');

      return wicac.conn()
      .url(`/login`) //호출 URL
      .param(credentials)
      .callback(function(result) {
          if(result.isError) {
            commit("SET_USER", '123123123');
            commit("SET_AUTHENTICATED", false);
            commit('SET_ERROR_MESSAGE', result.msg);
          } else {
            if(result.isSuccess) {
              commit("SET_USER", result.data.user);
              commit("SET_AUTHENTICATED", true);
            } else {
              commit("SET_USER", {});
              commit("SET_AUTHENTICATED", false);
              commit('SET_ERROR_MESSAGE', result.msg);
            }
          }
          return result; //결과값을 후 처리 할때 필수 선언
      })
      .post();
    },
    async getUser({ commit }) {
      return wicac.conn()
      .url('/api/users/me') //호출 URL
      .callback(function(result) {
          if(result.isError) {
            commit("SET_USER", {});
            commit("SET_AUTHENTICATED", false);
          } else {
            commit("SET_USER", result.data);
            commit("SET_AUTHENTICATED", true);
          }
      })
      .get();
    },
    async getAbilities({ commit }) {
      return wicac.conn()
      .url('/api/users/abilities') //호출 URL
      .callback(function(result) {
          if(result.isError) {
            commit("SET_USER_ACT", null);
          } else {
            commit("SET_USER_ACT", result.data);
          }
      })
      .get();
    },
    logout({ commit }) {
      commit("SET_USER", {});
      commit("SET_AUTHENTICATED", false);
      router.push({ name: "auth.login" });
    },
  },
};
