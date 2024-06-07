import axios from "axios";

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
      try {
        const { data } = await axios.post('/login', credentials);
        commit("SET_USER", data.data.user);
        commit("SET_AUTHENTICATED", true);
        return data.data.user;
      } catch (error) {
        if (error.response && error.response.data.message) {
          commit('SET_ERROR_MESSAGE', error.response.data.message);
        } else {
          commit('SET_ERROR_MESSAGE', '로그인 중 오류가 발생했습니다.');
        }
        commit("SET_USER", {});
        commit("SET_AUTHENTICATED", false);
        throw error;
      }
    },
    async getUser({ commit }) {
      try {
        const { data } = await axios.get('/api/users/me');
        commit("SET_USER", data.data);
        commit("SET_AUTHENTICATED", true);
      } catch (error) {
        commit("SET_USER", {});
        commit("SET_AUTHENTICATED", false);
      }
    },
    async getAbilities({ commit }) {
      try {
        const { data } = await axios.get('/api/users/abilities');
        commit("SET_USER_ACT", data.data);
      } catch (error) {
        // 오류 처리 필요 시 추가
      }
    },
    logout({ commit }) {
      commit("SET_USER", {});
      commit("SET_AUTHENTICATED", false);
      router.push({ name: "auth.login" });
    },
  },
};
